<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Created by PhpStorm.
 * User: CodeMonkey 1
 * Date: 04-02-2015
 * Time: 15:58
 */
class WebinarIgnition_Logs {

	const NOTICE     = 1;
	const LIVE_EMAIL = 2;
	const LIVE_SMS   = 3;
	const AUTO_EMAIL = 4;
	const AUTO_SMS   = 5;

	static $table_name     = 'wi_logs';
	static $per_page       = 10;
	static $show_pages     = 5;
	static $page           = 0;
	static $number_of_rows = 0;
	static $total          = 0;

	/**
	 * @return int
	 */
	public static function webinarignition_getPerPage() {
		return self::$per_page;
	}

	/**
	 * @param int $per_page
	 */
	public static function webinarignition_setPerPage( $per_page ) {
		self::$per_page = $per_page;
	}


	/**
	 * @return int
	 */
	public static function webinarignition_getShowPages() {
		return self::$show_pages;
	}

	/**
	 * @param int $show_pages
	 */
	public static function webinarignition_setShowPages( $show_pages ) {
		self::$show_pages = $show_pages;
	}

	/**
	 * @return int
	 */
	public static function webinarignition_getPage() {
		return self::$page;
	}

	/**
	 * @param int $page
	 */
	public static function webinarignition_setPage( $page ) {
		self::$page = $page;
	}

	/**
	 * @return int
	 */
	public static function webinarignition_getNumberOfRows() {
		return self::$number_of_rows;
	}

	/**
	 * @param int $number_of_rows
	 */
	public static function webinarignition_setNumberOfRows( $number_of_rows ) {
		self::$number_of_rows = $number_of_rows;
	}

	/**
	 * @return int
	 */
	public static function webinarignition_getTotal() {
		return self::$total;
	}

	/**
	 * @param int $total
	 */
	public static function webinarignition_setTotal( $total ) {
		self::$total = $total;
	}


	public static function add( $message, $campaign_id = null, $type = self::NOTICE ) {
		global $wpdb;
	
		// Ensure that the table name is correctly prefixed and concatenated directly into the SQL statement
		$table = $wpdb->prefix . self::$table_name;
		$queery = $wpdb->prepare(
			"INSERT INTO `{$table}` (campaign_id, type, message) VALUES (%d, %s, %s)",
			$campaign_id,
			$type,
			$message
		);
	
		$wpdb->query(
			$wpdb->prepare(
				"INSERT INTO `{$table}` (campaign_id, type, message) VALUES (%d, %s, %s)",
				$campaign_id,
				$type,
				$message
			)
		);
	}

	public static function webinarignition_deleteCampaignLogs( $campaign_id ) {
		global $wpdb;

		$table = $wpdb->prefix . self::$table_name;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $table WHERE campaign_id = %d",
				$campaign_id
			)
		);
	}

	/**
	 * Delete notifications logs older than 14 days
	 */
	public static function webinarignition_deleteOldLogs() {
		global $wpdb;

		$table = $wpdb->prefix . self::$table_name;
		$logs = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table",
				$table
			),
			ARRAY_A
		);		$now   = new DateTime( 'now' );

		foreach ( $logs as $log ) {
			$created    = new DateTime( $log['date'] );
			$difference = $created->diff( $now )->days;

			if ( $difference > 14 ) {
				$wpdb->delete( $table, array( 'campaign_id' => $log['campaign_id'] ) );
			}
		}
	}


	public static function webinarignition_showType( $type ) {
		$types = array(
			self::NOTICE     => 'Notice',
			self::LIVE_EMAIL => 'Live Email',
		);

		return $types[ $type ];
	}

	public static function webinarignition_getLogs( $campaign_id, $type, $page = 0, $timezone = false, $orderby = 'date', $orderbydirection = 'ASC' ) {
		global $wpdb;
		$table = $wpdb->prefix . self::$table_name;
		if ( is_array( $type ) ) {
			$type = implode( ' OR type = ', $type );
		}
		// echo "SELECT count(*) as total, date, message FROM $table WHERE campaign_id = $campaign_id AND type = $type ORDER BY $orderby $orderbydirection LIMIT $limit";
		$total = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT count(*) as total FROM {$table} WHERE campaign_id = %d AND type = %s",
				$campaign_id,
				$type
			)
		);
		if(isset($total)){
			self::webinarignition_setTotal( $total->total );
		}

		$date_querystr = 'date';
		if ( $timezone ) {
			$svr_tz  = date_default_timezone_get();
			$svr_utc = gmdate( 'P', time() );
			if ( $timezone != '' ) {
				$utc_to_time_abbr = webinarignition_utc_to_abrc( $timezone );
				
			}
			$webinar_utc = gmdate( 'P', time() );
			
			$date_querystr = "CONVERT_TZ(date,'{$svr_utc}','{$webinar_utc}') as date";
		}

		self::webinarignition_setPage( $page );

		$offset = ( $page - 1 ) * self::$per_page;
		$logs = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT {$date_querystr}, message FROM {$table} WHERE campaign_id = %d AND (type = %s) ORDER BY {$orderby} {$orderbydirection} LIMIT %d, %d",
				$campaign_id,
				$type,
				$offset,
				self::webinarignition_getPerPage()
			),
			OBJECT
		);
		self::webinarignition_setNumberOfRows( count( $logs ) );
		return $logs;
	}

	public static function webinarignition_pagination( $campaign_id ) {
		// "rows" => self::webinarignition_getNumberOfRows(),
		// "page" => self::webinarignition_getPage(),
		// "pages" => ceil(self::webinarignition_getTotal() / self::webinarignition_getPerPage()),
		// "total" => self::webinarignition_getTotal(),
		// "per_page" => self::webinarignition_getPerPage(),
		// );
		$number_of_pages_to_show = self::webinarignition_getShowPages();
		$current_page            = self::webinarignition_getPage();
		$current_last_page       = ceil( self::webinarignition_getTotal() / self::webinarignition_getPerPage() );
		$per_page                = self::webinarignition_getPerPage();
		$total_records           = self::webinarignition_getTotal();

		$first_page = 1;
		$prev_page  = $current_page - 1;
		$next_page  = $current_page + 1;
		$last_page  = $current_last_page;

		$first        = $current_page == $first_page;
		$last         = $current_page == $current_last_page;
		$first_record = $current_page * $per_page - $per_page + 1;
		$last_record  = $current_page * $per_page;
		$range        = $current_last_page >= $number_of_pages_to_show ? $number_of_pages_to_show : $current_last_page;
		$center       = ceil( $range / 2 );
		$start_page   = $current_page - $center;
		$last_page    = $current_last_page - $range;
		$start_page   = ( $start_page <= 0 ? 0 : ( $start_page >= $last_page ? $last_page : $start_page ) );

		if ( $last_record > $total_records ) {
			$last_record = $total_records;
		}

		if ( $total_records == 0 ) {
			$first_record = 0;
		}

		?>

		<div class="pagination clearfix">

				<a 
				<?php
				if ( ! $first ) {
					?>
					class="paginate" page="<?php echo esc_attr( $first_page ); } ?>" href="javascript:void(0);"> <?php esc_html_e( 'First', 'webinar-ignition' ); ?> </a>				
					<a 
					<?php
					if ( ! $first ) {
						?>
					class="paginate" page="<?php echo esc_attr( $prev_page ); ?>" <?php } ?>href="javascript:void(0);">«</a>
			<?php
			for ( $i = $start_page + 1; $i <= $start_page + $range; $i++ ) {
				if ( $current_page == $i ) {
					?>
					<strong><?php echo esc_html( $i ); ?></strong>

				<?php } else { ?>
					<a class="paginate" page="<?php echo esc_attr( $i ); ?>" href="javascript:void(0);"> <?php echo esc_html( $i ); ?> </a>
					<?php
				}
			}
			?>

			<a 
			<?php
			if ( ! $last ) {
				?>
				class="paginate" page="<?php echo esc_attr( $next_page ); } ?>" href="javascript:void(0);">»</a>
			<a 
			<?php
			if ( ! $last ) {
				?>
				<a class="paginate" page="<?php echo esc_attr( $current_last_page ); ?>"<?php } ?>href="javascript:void(0);"><?php esc_html_e( 'Last', 'webinar-ignition' ); ?></a>
		</div>

		<div style="margin: 10px 20px 0 0; padding: 0;">
			<span style="color: #444; font: 13px/1.7em Open Sans,trebuchet ms,arial,sans-serif;">
				<?php
					/* translators: %1$s: first record number, %2$s: last record number, %3$s: total number of records */
					echo esc_html(sprintf(
						/* translators: %1$s: first record number, %2$s: last record number, %3$s: total number of records */
						_n(
							'Showing %1$s to %2$s of %3$s entry',
							'Showing %1$s to %2$s of %3$s entries',
							$total_records,
							'webinar-ignition'
						),
						esc_html(number_format_i18n($first_record)),
						esc_html(number_format_i18n($last_record)),
						esc_html(number_format_i18n($total_records))
					));
				?>
				<?php
				if ( ! empty( $total_records ) ) :
					?>
					<button type="button" class="btn btn-danger" id="deleteLogs"><?php esc_html_e( 'Delete Logs', 'webinar-ignition' ); ?></button> <?php endif; ?>
			</span>
		</div>

		<?php
	}
}