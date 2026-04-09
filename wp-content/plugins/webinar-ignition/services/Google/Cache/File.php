<?php

defined( 'ABSPATH' ) || exit; 
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once "Google/Cache/Abstract.php";
require_once "Google/Cache/Exception.php";

/*
 * This class implements a basic on disk storage. While that does
 * work quite well it's not the most elegant and scalable solution.
 * It will also get you into a heap of trouble when you try to run
 * this in a clustered environment.
 *
 * @author Chris Chabot <chabotc@google.com>
 */
class Google_Cache_File extends Google_Cache_Abstract
{
  const MAX_LOCK_RETRIES = 10;
  private $path;
  private $fh;

  public function __construct(Google_Client $client)
  {
    $this->path = $client->getClassConfig($this, 'directory');
  }
  
  public function get($key, $expiration = false) {
    // Include the WordPress Filesystem API
    if (!function_exists('request_filesystem_credentials')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    WP_Filesystem();
    global $wp_filesystem;

    // Determine the cache file path
    $storageFile = $this->getCacheFile($key);
    $data = false;

    // Check if the file exists
    if (!$wp_filesystem->exists($storageFile)) {
        return false;
    }

    // Handle expiration logic
    if ($expiration) {
        $mtime = $wp_filesystem->mtime($storageFile);
        $now = time();
        if (($now - $mtime) >= $expiration) {
            $this->delete($key);  // Ensure this method uses WP_Filesystem as well
            return false;
        }
    }

    // Read data if possible
    if ($this->acquireReadLock($storageFile)) {
        $data = $wp_filesystem->get_contents($storageFile);
        $data = maybe_unserialize($data);
        $this->unlock($storageFile);
    }

    return $data;
}


public function set($key, $value)
{
    global $wp_filesystem;

    // Initialize the WordPress filesystem, ABSPATH should be passed to the filesystem method
    if (empty($wp_filesystem)) {
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $storageFile = $this->getWriteableCacheFile($key);
    if ($this->acquireWriteLock($storageFile)) {
        // Serialize the whole request object
        $data = serialize($value);
        
        // Use the WP_Filesystem to write data
        $result = $wp_filesystem->put_contents(
            $storageFile,
            $data,
            FS_CHMOD_FILE // predefined file permissions
        );

        $this->unlock($storageFile);
    }
}


public function delete($key)
{
    $file = $this->getCacheFile($key);

    if (file_exists($file)) {
        // Use WordPress's wp_delete_file() function to delete the file
        if (!wp_delete_file($file)) {
            throw new Google_Cache_Exception("Cache file could not be deleted");
        }
    }
}
  
  private function getWriteableCacheFile($file)
  {
    return $this->getCacheFile($file, true);
  }

  private function getCacheFile($file, $forWrite = false)
  {
    return $this->getCacheDir($file, $forWrite) . '/' . md5($file);
  }
  
  private function getCacheDir($file, $forWrite)
{
    global $wp_filesystem;

    // Initialize the WordPress filesystem, if not already.
    if (empty($wp_filesystem)) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        WP_Filesystem();
    }

    // Use the first 2 characters of the hash as a directory prefix
    $storageDir = $this->path . '/' . substr(md5($file), 0, 2);
    
    if ($forWrite && ! $wp_filesystem->is_dir($storageDir)) {
        if (! $wp_filesystem->mkdir($storageDir, 0755)) {
            throw new Google_Cache_Exception("Could not create storage directory: " . esc_html($storageDir));
        }
    }
    
    return $storageDir;
}

  
  private function acquireReadLock($storageFile)
  {
    return $this->acquireLock(LOCK_SH, $storageFile);
  }
  
  private function acquireWriteLock($storageFile)
  {
    $rc = $this->acquireLock(LOCK_EX, $storageFile);
    if (!$rc) {
      $this->delete($storageFile);
    }
    return $rc;
  }
  
  private function acquireLock($type, $storageFile)
{
    $lockKey = 'lock_' . md5($storageFile);
    $count = 0;
    $lock = get_transient($lockKey);

    // Attempt to acquire lock
    while ($lock !== false) {
        usleep(10000); // Sleep for 10ms
        if (++$count >= self::MAX_LOCK_RETRIES) {
            return false; // Fail to acquire lock after max retries
        }
        $lock = get_transient($lockKey);
    }

    // Set the lock, expire after 1 minute to avoid deadlocks
    set_transient($lockKey, true, MINUTE_IN_SECONDS);
    return true;
}

private function releaseLock($storageFile)
{
    $lockKey = 'lock_' . md5($storageFile);
    delete_transient($lockKey);
}

  
  public function unlock($storageFile)
  {
    if ($this->fh) {
      flock($this->fh, LOCK_UN);
    }
  }
}
