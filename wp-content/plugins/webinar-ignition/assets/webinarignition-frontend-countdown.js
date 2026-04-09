(function ($) {
	$(document).ready(function() {
		const FULL_DASH_ARRAY = 283;
		const WARNING_THRESHOLD = 10;
		const ALERT_THRESHOLD = 5;

		const COLOR_CODES = {
			info: { color: "green" },
			warning: { color: "orange", threshold: WARNING_THRESHOLD },
			alert: { color: "red", threshold: ALERT_THRESHOLD },
		};

		var WI_TIME_LIMIT = wi_data.time_limit;
		var webinar_type = wi_data.webinar_type;
		var wi_timeover_timeLeft = wi_data.time_limit;
		var show_timer = wi_data.show_timer;
		let remainingPathColor = COLOR_CODES.info.color;
		var wi_paused_interval = false;
		
		// Video element (assuming you have a video element on the page)
		const videoElement = document.querySelector("video");

		// Start countdown based on video timing
		jQuery(document.body).on("wi_start_timeout_countdown", function () {
			document.getElementById("wi_count_down_5_mint").innerHTML = `
			<div class="base-timer">
				<svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
				<g class="base-timer__circle">
					<circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
					<path
					id="base-timer-path-remaining"
					stroke-dasharray="283"
					class="base-timer__path-remaining ${remainingPathColor}"
					d="M 50, 50 m -45, 0 a 45,45 0 1,0 90,0 a 45,45 0 1,0 -90,0"
					></path>
				</g>
				</svg>
				<span id="base-timer-label" class="base-timer__label">${wi_timeover_formatTime(wi_timeover_timeLeft)}</span>
			</div>
			`;

		});
		if( show_timer && webinar_type == 'live' ) {
			document.getElementById("wi_count_down_5_mint").style.display = 'block';
			// Update the timer label and progress
			var wi_timeover_totalTime = 45 * 60; // Total time in seconds (45 minutes)
			var wi_timeover_timeLeft = wi_timeover_totalTime; // Start with the full time
		
			function wi_timeover_formatTime(seconds) {
				const minutes = Math.floor(seconds / 60);
				const remainingSeconds = seconds % 60;
				return `${minutes}:${remainingSeconds < 10 ? "0" : ""}${remainingSeconds}`;
			}
			const FULL_DASH_ARRAY = 283; // Full length of the SVG path

			function wi_timeover_calculateTimeFraction() {
				// Assuming wi_timeover_timeLeft and wi_timeover_totalTime are defined
				// wi_timeover_totalTime should be set to 45 minutes in seconds (2700 seconds)
				return wi_timeover_timeLeft / wi_timeover_totalTime;
			}
			
			function wi_timeover_setCircleDasharray_live() {
				// Calculate the percentage of the remaining time
				const circleDasharray = `${(wi_timeover_calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} 283`;
			
				// Update the SVG element with the calculated dasharray
				var element = document.getElementById("base-timer-path-remaining");
				if (element) {
					element.setAttribute("stroke-dasharray", circleDasharray);
				}
			}
			function updateTimer() {
				if (wi_timeover_timeLeft > 0) {
					wi_timeover_timeLeft--;
		
					// Update the timer label
					var timerLabel = $("#base-timer-label");
					if (timerLabel.length) {
						timerLabel.html(wi_timeover_formatTime(wi_timeover_timeLeft));
					}
		
					// Update the circle dasharray
					wi_timeover_setCircleDasharray_live();
				} else {
					clearInterval(timerInterval);
					// Add any logic for when the timer finishes
				}
			}
		
			// Run the updateTimer function every second
			var timerInterval = setInterval(updateTimer, 1000);

		}
		if (wi_data.is_preview || wi_data.webinar_live_video) {
			WI_TIME_LIMIT = wi_data.time_limit;
			wi_timeover_timeLeft = wi_data.time_limit;
			jQuery(document.body).trigger("wi_start_timeout_countdown");
		}

		// Attach an event listener to the video element to sync the timer with the video
		if (videoElement) {
			videoElement.addEventListener("timeupdate", () => {
				if (!wi_paused_interval) {
					const videoCurrentTime = videoElement.currentTime;
					const videoDuration = videoElement.duration;

					wi_timeover_timeLeft = WI_TIME_LIMIT - videoCurrentTime;

					// Show the timer only when there are 5 minutes or less remaining
					if (wi_timeover_timeLeft <= 300) {
						document.getElementById("wi_count_down_5_mint").style.display = 'block';
					} else {
						document.getElementById("wi_count_down_5_mint").style.display = 'none';
					}

					// If the time is up, stop everything
					if (wi_timeover_timeLeft <= 0) {
						wi_timeover_onTimesUp();
						return;
					}

					// Update the timer label and progress
					var timerLabel = document.getElementById("base-timer-label");
					if (timerLabel) {
						timerLabel.innerHTML = wi_timeover_formatTime(wi_timeover_timeLeft);
					}
					wi_timeover_setCircleDasharray();
					setRemainingPathColor(wi_timeover_timeLeft);
				}
			});	
		}

		function wi_timeover_onTimesUp() {
			videoElement.pause(); // Optionally pause the video when the timer is up
		}

		function wi_timeover_formatTime(time) {
			const minutes = Math.floor(time / 60);
			let seconds = Math.floor(time % 60);

			if (seconds < 10) {
				seconds = `0${seconds}`;
			}

			return `${minutes}:${seconds}`;
		}

		function setRemainingPathColor(wi_timeover_timeLeft) {
			const { alert, warning, info } = COLOR_CODES;
			if (wi_timeover_timeLeft <= alert.threshold) {
				var pathRemainingElement = document.getElementById("base-timer-path-remaining");
				if (pathRemainingElement) {
					pathRemainingElement.classList.remove(warning.color);
				}
				var pathRemainingElement = document.getElementById("base-timer-path-remaining");
				if (pathRemainingElement) {
					pathRemainingElement.classList.add(alert.color);
				}
			} else if (wi_timeover_timeLeft <= warning.threshold) {
				var pathRemainingElement = document.getElementById("base-timer-path-remaining");
				if (pathRemainingElement) {
					pathRemainingElement.classList.remove(info.color);
				}
				var pathRemainingElement = document.getElementById("base-timer-path-remaining");
				if (pathRemainingElement) {
					pathRemainingElement.classList.add(warning.color);
				}
			}
		}

		function wi_timeover_calculateTimeFraction() {
			const rawTimeFraction = wi_timeover_timeLeft / WI_TIME_LIMIT;
			return rawTimeFraction - (1 / WI_TIME_LIMIT) * (1 - rawTimeFraction);
		}

		function wi_timeover_setCircleDasharray() {
			const circleDasharray = `${(
				wi_timeover_calculateTimeFraction() * FULL_DASH_ARRAY
			).toFixed(0)} 283`;
			var element = document.getElementById("base-timer-path-remaining");
			if (element) {
				element.setAttribute("stroke-dasharray", circleDasharray);
			}
		}
	});
})(jQuery);
