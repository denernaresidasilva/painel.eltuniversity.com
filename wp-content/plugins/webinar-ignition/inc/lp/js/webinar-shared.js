
(function ($) {
    var ajaxurl = wiJsObj.ajaxurl;

    var chatStarted = false;
    var chatBlocked = false;

    $('#chatQuestion').on('click', function () {
        var btn = $(this);

        var question = $("#question").val().trim();

        if (question === "") {
            $("#question").addClass("errorField");
        } else {
            $("#question").removeClass("errorField");

            var name = $("#optName").val();
            var email = $("#optEmail").val();
            var lead = $("#leadID").val();
            var app_id = btn.data('app_id');
            var video_live_time = btn.data('video_live_time');
                video_live_time        = new Date(video_live_time).getTime();
            var timeNow                = Date.now();
            var timeDifference         = timeNow-video_live_time;
            var webinarTime            = (timeDifference)/60000;
            var is_first_question      = localStorage.getItem('webinar_'+app_id+'_question_submitted') ? false : true;
            var last_message = false;

            var last_message_container = $('#chatQAMessages .wi_msg_container:last-child');

            if (last_message_container.length) {
                last_message = $(last_message_container[0]).data('id');
            }

            var data = {
                action: 'webinarignition_submit_chat_question',
                app_id: app_id,
                security: wiJsObj.ajax_nonce,
                name: name,
                email: email,
                question: question,
                lead: lead,
                is_first_question: lead,
                webinarTime: webinarTime.toFixed()
            };

            if (last_message) {
                data.last_message = last_message;
            }

            ajaxRequest(data, function(response) {
                if(is_first_question && response) {
                    localStorage.setItem('webinar_'+app_id+'_question_submitted', true);
                }

                if (response.chat_messages && response.chat_messages.length) {
                    $.each(response.chat_messages, function(i, message) {
                        appendChatMessage(message);
                    });

                    $("#chatQAMessages").scrollTop($("#chatQAMessages")[0].scrollHeight);
                }

                if (response.chat_messages_deleted && response.chat_messages_deleted.length) {
                    deleteChatMessages(response.chat_messages_deleted);
                }

                $("#question").val('');

                chatStarted = true;
            }, function(response) {
                console.log(response);
            });
        }
    });

    $(document).ready(function () {
        loadChatMessages();
    });

    $(window).on('resize', function () {});

    $(window).on('load', function (event) {});

    $(window).on('scroll', function (event) {});

    $(document).on('wi_ajax_request_event', function (e) {
      ajaxRequest(e.originalEvent.detail);
    });

    function loadChatMessages() {
        var chatQArea = $('#chatQArea');
        if (!chatQArea.length) return true;

        var app_id = chatQArea.data('app_id');
        var email = chatQArea.data('email');

        ajaxRequest({
            action: 'webinarignition_load_chat_messages',
            app_id: app_id,
            email: email,
            security: wiJsObj.ajax_nonce
        }, function(response) {
            if (response.chat_messages && response.chat_messages.length) {
                $.each(response.chat_messages, function(i, message) {
                    appendChatMessage(message);
                });

                $("#chatQAMessages").scrollTop($("#chatQAMessages")[0].scrollHeight);

                chatStarted = true;
            }

            refreshChatMessages();
        }, function(response) {
            console.log(response);
        });
    }

    function refreshChatMessages() {
        var chatQArea = $('#chatQArea');
        if (!chatQArea.length) return true;
        var period = chatQArea.data('refresh') * 1000;

        if (!chatStarted) {
            setTimeout(function() {
                refreshChatMessages();
            }, period);
        } else {
            var email = chatQArea.data('email');
            var app_id = chatQArea.data('app_id');
            var lead = $("#leadID").val();
            var last_message = false;

            var last_message_container = $('#chatQAMessages .wi_msg_container:last-child');

            if (last_message_container.length) {
                last_message = $(last_message_container[0]).data('id');
            }

            var data = {
                action: 'webinarignition_refresh_chat_messages',
                app_id: app_id,
                email: email,
                security: wiJsObj.ajax_nonce
            };

            if (last_message) {
                data.last_message = last_message;
            }

            ajaxRequest(data, function(response) {
                if (response.chat_messages && response.chat_messages.length) {
                    $.each(response.chat_messages, function(i, message) {
                        appendChatMessage(message);
                    });

                    $("#chatQAMessages").scrollTop($("#chatQAMessages")[0].scrollHeight);
                }

                if (response.chat_messages_deleted && response.chat_messages_deleted.length) {
                    deleteChatMessages(response.chat_messages_deleted);
                }

                setTimeout(function() {
                    refreshChatMessages();
                }, period);
            }, function(response) {
                setTimeout(function() {
                    refreshChatMessages();
                }, period);

                console.log(response);
            });
        }
    }

    function ajaxRequest(data, cb, cbError) {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: data,
            success: function (response) {
                var decoded;

                try {
                    decoded = $.parseJSON(response);
                } catch(err) {
                    console.log(err);
                    decoded = false;
                }

                if (decoded) {
                    if (decoded.success) {
                        if (decoded.message) {
                            alert(decoded.message);
                        }

                        if (decoded.url) {
                            window.location.replace(decoded.url);
                        } else if (decoded.reload) {
                            window.location.reload();
                        }

                        if (typeof cb === 'function') {
                            cb(decoded);
                        }
                    } else {
                        if (decoded.message) {
                            // alert(decoded.message);
							let currentUrl = window.location.href;
    
							// Remove variables (query string)
							let cleanUrl = currentUrl.split('?')[0];
							window.location.href = cleanUrl;
                        }

                        if (typeof cbError === 'function') {
                            cbError(decoded);
                        }
                    }
                } else {
                    alert(wiJsObj.someWrong);
                }
            }
        });
    }

    function deleteChatMessages(messages) {
        if (messages.length) {
            $.each(messages, function(i, message) {
                var message_to_delete = $('#wi_msg_'+message.ID);

                if (message_to_delete.length) {
                    message_to_delete.remove();
                }
            });
        }
    }

    function appendChatMessage(message) {
        var messages_container = $('#chatQAMessages');
        var type = 'outgoing';

        if (message.type) {
            type = message.type;
        }

        var html = '';
            html += '<div id="wi_msg_'+message.ID+'" class="wi_msg_container wi_msg_'+type+'" data-id="'+message.ID+'">';
            html += '<div class="wi_msg">';

            if (message.author) {
                html += '<div class="wi_msg_author">'+message.author+'</div>';
            }

            html += message.question+'</div>';

            if (message.created) {
                html += '<div class="wi_msg_clear"></div>';
                html += '<div class="wi_msg_meta">';

                if (message.created) {
                    html += '<div class="wi_msg_created">'+message.created+'</div>';
                }

                html += '</div>';
            }

            html += '</div>';

        messages_container.append(html);
        var answerBgColor = $('#chatQAMessages').data('wimsg-bg');
        var answerTextColor = $('#chatQAMessages').data('wimsg-color');
    
        // Apply styles dynamically to the chat messages
        $('#chatQAMessages .wi_msg').css({
            'background-color': answerBgColor,
            'color': answerTextColor
        });

        $('#chatQAMessages .wi_msg_outgoing .wi_msg').css({
            'background-color': answerBgColor,
            'color': answerTextColor
        });

    }
})(jQuery);

// Countdown timer
function createCountdownTimer() {
  return {
    FULL_DASH_ARRAY: 283,
    COLOR_CODES: {
      info: { color: 'green' },
      warning: { color: 'orange', threshold: 10 },
      alert: { color: 'red', threshold: 5 },
    },
    timerContainer: null,
    totalTime: 0,
    timeLeft: 0,
    timerInterval: null,
    timeUpCb: null,

    init: function (containerId, initialValue, maxValue, timeUpCb = null) {
      this.timerContainer = document.getElementById(containerId);
      this.totalTime = maxValue;
      this.timeLeft = initialValue;
      this.timeUpCb = timeUpCb;
      this.createTimer();
      this.updateTimerDisplay();
    },

    start: function () {
      console.log('Timer starts');
      if (this.timerContainer) this.timerContainer.style.display = 'block';

      this.timerInterval = setInterval(() => {
        if (this.timeLeft > 0) {
          this.timeLeft--;
          this.updateTimerDisplay();
        } else {
          this.handleTimeUp();
        }
      }, 1000);
    },

    stop: function () {
      clearInterval(this.timerInterval);
    },

    createTimer: function () {
      this.timerContainer.innerHTML = `
        <div class="wi_base-timer">
          <svg class="wi_base-timer__svg" viewBox="0 0 100 100">
            <g class="wi_base-timer__circle">
              <circle class="wi_base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
              <path id="wi_base-timer-path-remaining" stroke-dasharray="${
                this.FULL_DASH_ARRAY
              }" class="wi_base-timer__path-remaining ${
        this.COLOR_CODES.info.color
      }" d="M 50, 50 m -45, 0 a 45,45 0 1,0 90,0 a 45,45 0 1,0 -90,0"></path>
            </g>
          </svg>
          <span id="wi_base-timer-label" class="wi_base-timer__label">${this.formatTime(this.timeLeft)}</span>
        </div>`;
    },

    formatTime: function (time) {
      const minutes = Math.floor(time / 60);
      const seconds = String(time % 60).padStart(2, '0');
      return `${minutes}:${seconds}`;
    },

    calculateTimeFraction: function () {
      return this.timeLeft / this.totalTime;
    },

    setCircleDasharray: function () {
      const circleDasharray = `${(this.calculateTimeFraction() * this.FULL_DASH_ARRAY).toFixed(0)} 283`;
      const element = document.getElementById('wi_base-timer-path-remaining');
      if (element) element.setAttribute('stroke-dasharray', circleDasharray);
    },

    setRemainingPathColor: function () {
      const path = document.getElementById('wi_base-timer-path-remaining');
      if (!path) return;

      path.classList.remove(this.COLOR_CODES.info.color, this.COLOR_CODES.warning.color, this.COLOR_CODES.alert.color);

      if (this.timeLeft <= this.COLOR_CODES.alert.threshold) {
        path.classList.add(this.COLOR_CODES.alert.color);
      } else if (this.timeLeft <= this.COLOR_CODES.warning.threshold) {
        path.classList.add(this.COLOR_CODES.warning.color);
      } else {
        path.classList.add(this.COLOR_CODES.info.color);
      }
    },

    updateTimerDisplay: function () {
      const label = document.getElementById('wi_base-timer-label');
      if (label) label.innerHTML = this.formatTime(this.timeLeft);
      this.setCircleDasharray();
      this.setRemainingPathColor();
    },

    handleTimeUp: function () {
      clearInterval(this.timerInterval);
      if (this.timeUpCb) this.timeUpCb();
    },
  };
}

// Make available globally
window.createCountdownTimer = createCountdownTimer;


