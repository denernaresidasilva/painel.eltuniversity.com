function _objectWithoutPropertiesLoose(source, excluded) {
    if (source == null) return {};
    var target = {};
    var sourceKeys = Object.keys(source);
    var key, i;

    for (i = 0; i < sourceKeys.length; i++) {
        key = sourceKeys[i];
        if (excluded.indexOf(key) >= 0) continue;
        target[key] = source[key];
    }

    return target;
}

var _excluded = ["containerId"];
var VideoSDKMeeting = /*#__PURE__*/function () {
    function VideoSDKMeeting() {}

    var _proto = VideoSDKMeeting.prototype;

    _proto.init = function init(_temp) {
        var _ref = _temp === void 0 ? {} : _temp,
            containerId = _ref.containerId,
            rest = _objectWithoutPropertiesLoose(_ref, _excluded);

        try {
            var _this2 = this;

            var myDocument = parent.document;
            var myWindow = parent.window;

            if (typeof myWindow === "undefined" || typeof myDocument === "undefined") {
                throw new Error("No browser detected!");
            }

            return Promise.resolve(_this2.generatePrebuiltSrc(rest, myWindow, myDocument)).then(function (prebuiltSrc) {
                var iframe_id = "videosdk-frame";
                var meetingFrame = myDocument.createElement("iframe");
                meetingFrame.id = iframe_id;
                meetingFrame.src = prebuiltSrc;
                meetingFrame.allowfullscreen = true;
                meetingFrame.width = "100%";
                meetingFrame.height = "100%";
                meetingFrame.allow = "camera *; microphone *; fullscreen; display-capture; allow-same-origin; allow-presentation; encrypted-media; midi; encrypted-media ";
                meetingFrame.style.border = 0;
                meetingFrame.allowusermedia = "allowusermedia";
                var iframeContainer = null;

                if (containerId) {
                    var container = myDocument.getElementById(containerId);

                    if (!container) {
                        throw new Error("No Container found with id " + containerId);
                    }

                    iframeContainer = container;
                    container.appendChild(meetingFrame);
                } else {
                    var _container = myDocument.createElement("div");

                    _container.style.position = "fixed";
                    _container.style.left = 0;
                    _container.style.right = 0;
                    _container.style.bottom = 0;
                    _container.style.top = 0;
                    iframeContainer = _container;

                    _container.appendChild(meetingFrame);

                    myDocument.body.style.margin = "0px";
                    myDocument.body.style.padding = "0px";
                    myDocument.body.style.height = "100%";
                    myDocument.body.style.overflow = "hidden";
                    myDocument.body.appendChild(_container);
                }

                window.dispatchEvent(new Event('video_sdk_iframe_loaded'));
                myWindow.addEventListener("popstate", function (e) {
                    iframeContainer.remove();
                });
            });
        } catch (e) {
            return Promise.reject(e);
        }
    };

    _proto.fetchToken = function fetchToken(_ref2) {
        var apiKey = _ref2.apiKey,
            askJoin = _ref2.askJoin,
            participantCanToggleOtherWebcam = _ref2.participantCanToggleOtherWebcam,
            participantCanToggleOtherMic = _ref2.participantCanToggleOtherMic,
            partcipantCanToogleOtherScreenShare = _ref2.partcipantCanToogleOtherScreenShare,
            apiBaseUrl = _ref2.apiBaseUrl;

        try {
            var BASE_URL = apiBaseUrl || "https://api.videosdk.live";
            var urlToken = BASE_URL + "/v1/prebuilt/token";
            var permissions = [];

            if (askJoin) {
                permissions.push("ask_join");
            } else {
                permissions.push("allow_join");
            }

            if (participantCanToggleOtherWebcam || participantCanToggleOtherMic || partcipantCanToogleOtherScreenShare) {
                permissions.push("allow_mod");
            }

            var tokenBody = {
                apiKey: apiKey
            };

            if (permissions.length) {
                tokenBody["permissions"] = permissions;
            }

            return Promise.resolve(fetch(urlToken, {
                method: "POST",
                headers: {
                    "Content-type": "application/json"
                },
                body: JSON.stringify(tokenBody)
            })).then(function (res) {
                var _exit = false;
                var token;

                var _temp2 = function () {
                    if (res.status === 200) {
                        return Promise.resolve(res.json()).then(function (json) {
                            token = json.token;
                        });
                    } else {
                        throw new Error("Could not fetch token.");
                    }
                }();

                return _temp2 && _temp2.then ? _temp2.then(function (_result) {
                    return _exit ? _result : token;
                }) : _exit ? _temp2 : token;
            });
        } catch (e) {
            return Promise.reject(e);
        }
    };

    _proto.generatePrebuiltSrc = function generatePrebuiltSrc(_temp4, myWindow) {
        var _ref4 = _temp4 === void 0 ? {} : _temp4,
            name = _ref4.name,
            apiKey = _ref4.apiKey,
            meetingId = _ref4.meetingId,
            token = _ref4.token,
            region = _ref4.region,
            preferredProtocol = _ref4.preferredProtocol,
            redirectOnLeave = _ref4.redirectOnLeave,
            micEnabled = _ref4.micEnabled,
            webcamEnabled = _ref4.webcamEnabled,
            participantCanToggleSelfWebcam = _ref4.participantCanToggleSelfWebcam,
            participantCanToggleSelfMic = _ref4.participantCanToggleSelfMic,
            participantTabPanelEnabled = _ref4.participantTabPanelEnabled,
            moreOptionsEnabled = _ref4.moreOptionsEnabled,
            participantCanLeave = _ref4.participantCanLeave,
            chatEnabled = _ref4.chatEnabled,
            screenShareEnabled = _ref4.screenShareEnabled,
            pollEnabled = _ref4.pollEnabled,
            whiteboardEnabled = _ref4.whiteboardEnabled,
            raiseHandEnabled = _ref4.raiseHandEnabled,
            theme = _ref4.theme,
            branding = _ref4.branding,
            livestream = _ref4.livestream,
            recording = _ref4.recording,
            hls = _ref4.hls,
            waitingScreen = _ref4.waitingScreen,
            initPermissions = _ref4.permissions,
            joinScreen = _ref4.joinScreen,
            leftScreen = _ref4.leftScreen,
            layout = _ref4.layout,
            maxResolution = _ref4.maxResolution,
            debug = _ref4.debug,
            isRecorder = _ref4.isRecorder,
            videoConfig = _ref4.videoConfig,
            screenShareConfig = _ref4.screenShareConfig,
            audioConfig = _ref4.audioConfig,
            i18n = _ref4.i18n,
            maintainVideoAspectRatio = _ref4.maintainVideoAspectRatio,
            maintainLandscapeVideoAspectRatio = _ref4.maintainLandscapeVideoAspectRatio,
            networkBarEnabled = _ref4.networkBarEnabled,
            participantId = _ref4.participantId,
            meetingLayoutTopic = _ref4.meetingLayoutTopic,
            joinWithoutUserInteraction = _ref4.joinWithoutUserInteraction,
            notificationSoundEnabled = _ref4.notificationSoundEnabled,
            notificationAlertsEnabled = _ref4.notificationAlertsEnabled,
            participantNotificationAlertsEnabled = _ref4.participantNotificationAlertsEnabled,
            animationsEnabled = _ref4.animationsEnabled,
            topbarEnabled = _ref4.topbarEnabled,
            hideLocalParticipant = _ref4.hideLocalParticipant,
            alwaysShowOverlay = _ref4.alwaysShowOverlay,
            sideStackSize = _ref4.sideStackSize,
            reduceEdgeSpacing = _ref4.reduceEdgeSpacing,
            _embedBaseUrl = _ref4.embedBaseUrl,
            apiBaseUrl = _ref4.apiBaseUrl,
            mode = _ref4.mode,
            realtimeTranscription = _ref4.realtimeTranscription;

        try {
            var _temp5 = function _temp5(_this3$fetchToken) {
                var _myWindow$navigator;

                token = _this3$fetchToken;
                var rawUserAgent = myWindow === null || myWindow === void 0 ? void 0 : (_myWindow$navigator = myWindow.navigator) === null || _myWindow$navigator === void 0 ? void 0 : _myWindow$navigator.userAgent;
                var prebuiltSrcQueryParameters = [{
                    key: "micEnabled",
                    value: micEnabled ? "true" : "false"
                }, {
                    key: "webcamEnabled",
                    value: webcamEnabled ? "true" : "false"
                }, {
                    key: "name",
                    value: name
                }, {
                    key: "meetingId",
                    value: meetingId || ""
                }, {
                    key: "region",
                    value: region || "sg001"
                }, {
                    key: "preferredProtocol",
                    value: preferredProtocol || "UDP_ONLY"
                }, {
                    key: "canChangeLayout",
                    value: canChangeLayout ? "true" : "false"
                }, {
                    key: "redirectOnLeave",
                    value: redirectOnLeave || ""
                }, {
                    key: "chatEnabled",
                    value: chatEnabled ? "true" : "false"
                }, {
                    key: "theme",
                    value: theme || "DEFAULT"
                }, {
                    key: "language",
                    value: language || "en"
                }, {
                    key: "screenShareEnabled",
                    value: screenShareEnabled ? "true" : "false"
                }, {
                    key: "pollEnabled",
                    value: typeof pollEnabled === "boolean" ? pollEnabled ? "true" : "false" : "true"
                }, {
                    key: "whiteboardEnabled",
                    value: whiteboardEnabled ? "true" : "false"
                }, {
                    key: "participantCanToggleSelfWebcam",
                    value: participantCanToggleSelfWebcam ? "true" : "false"
                }, {
                    key: "participantCanToggleSelfMic",
                    value: participantCanToggleSelfMic ? "true" : "false"
                }, {
                    key: "raiseHandEnabled",
                    value: raiseHandEnabled ? "true" : "false"
                }, {
                    key: "token",
                    value: token || ""
                }, {
                    key: "recordingEnabled",
                    value: recordingEnabled ? "true" : "false"
                }, {
                    key: "recordingWebhookUrl",
                    value: recordingWebhookUrl || ""
                }, {
                    key: "recordingAWSDirPath",
                    value: recordingAWSDirPath || ""
                }, {
                    key: "autoStartRecording",
                    value: autoStartRecording ? "true" : "false"
                }, {
                    key: "recordingTheme",
                    value: recordingTheme || "DEFAULT"
                }, {
                    key: "participantCanToggleRecording",
                    value: typeof participantCanToggleRecording === "boolean" ? participantCanToggleRecording ? "true" : "false" : false
                }, {
                    key: "brandingEnabled",
                    value: brandingEnabled ? "true" : "false"
                }, {
                    key: "brandLogoURL",
                    value: brandLogoURL || ""
                }, {
                    key: "brandName",
                    value: brandName
                }, {
                    key: "participantCanLeave",
                    value: typeof participantCanLeave === "boolean" ? participantCanLeave ? "true" : "false" : "true"
                }, {
                    key: "poweredBy",
                    value: typeof poweredBy === "boolean" ? poweredBy ? "true" : "false" : "true"
                }, {
                    key: "liveStreamEnabled",
                    value: liveStreamEnabled ? "true" : "false"
                }, {
                    key: "autoStartLiveStream",
                    value: autoStartLiveStream ? "true" : "false"
                }, {
                    key: "liveStreamOutputs",
                    value: JSON.stringify(liveStreamOutputs || [])
                }, {
                    key: "liveStreamTheme",
                    value: liveStreamTheme || "DEFAULT"
                }, {
                    key: "participantCanToggleOtherMic",
                    value: participantCanToggleOtherMic ? "true" : "false"
                }, {
                    key: "participantTabPanelEnabled",
                    value: typeof participantTabPanelEnabled === "boolean" ? participantTabPanelEnabled ? "true" : "false" : "true"
                }, {
                    key: "moreOptionsEnabled",
                    value: typeof moreOptionsEnabled === "boolean" ? moreOptionsEnabled ? "true" : "false" : "true"
                }, {
                    key: "partcipantCanToogleOtherScreenShare",
                    value: partcipantCanToogleOtherScreenShare ? "true" : "false"
                }, {
                    key: "participantCanToggleOtherWebcam",
                    value: participantCanToggleOtherWebcam ? "true" : "false"
                }, {
                    key: "participantCanToggleOtherMode",
                    value: participantCanToggleOtherMode ? "true" : "false"
                }, {
                    key: "askJoin",
                    value: askJoin ? "true" : "false"
                }, {
                    key: "joinScreenEnabled",
                    value: joinScreenEnabled ? "true" : "false"
                }, {
                    key: "joinScreenMeetingUrl",
                    value: joinScreenMeetingUrl || ""
                }, {
                    key: "joinScreenTitle",
                    value: joinScreenTitle || ""
                }, {
                    key: "notificationSoundEnabled",
                    value: typeof notificationSoundEnabled === "boolean" ? notificationSoundEnabled ? "true" : "false" : "true"
                }, {
                    key: "canPin",
                    value: canPin ? "true" : "false"
                }, {
                    key: "canCreatePoll",
                    value: canCreatePoll ? "true" : "false"
                }, {
                    key: "canToggleParticipantTab",
                    value: typeof canToggleParticipantTab === "boolean" ? canToggleParticipantTab ? "true" : "false" : "true"
                }, {
                    key: "layoutType",
                    value: layoutType
                }, {
                    key: "mode",
                    value: mode
                }, {
                    key: "participantCanEndMeeting",
                    value: typeof participantCanEndMeeting === "boolean" ? participantCanEndMeeting ? "true" : "false" : "false"
                }, {
                    key: "canDrawOnWhiteboard",
                    value: typeof canDrawOnWhiteboard === "boolean" ? canDrawOnWhiteboard ? "true" : "false" : "true"
                }, {
                    key: "canToggleWhiteboard",
                    value: typeof canToggleWhiteboard === "boolean" ? canToggleWhiteboard ? "true" : "false" : "true"
                }, {
                    key: "canToggleVirtualBackground",
                    value: typeof canToggleVirtualBackground === "boolean" ? canToggleVirtualBackground ? "true" : "false" : "true"
                }, {
                    key: "canRemoveOtherParticipant",
                    value: typeof canRemoveOtherParticipant === "boolean" ? canRemoveOtherParticipant ? "true" : "false" : "false"
                }, {
                    key: "leftScreenActionButtonLabel",
                    value: leftScreenActionButtonLabel
                }, {
                    key: "leftScreenActionButtonHref",
                    value: leftScreenActionButtonHref
                }, {
                    key: "maxResolution",
                    value: maxResolution || "sd"
                }, {
                    key: "animationsEnabled",
                    value: typeof animationsEnabled === "boolean" ? animationsEnabled : true
                }, {
                    key: "topbarEnabled",
                    value: typeof topbarEnabled === "boolean" ? topbarEnabled : true
                }, {
                    key: "notificationAlertsEnabled",
                    value: typeof notificationAlertsEnabled === "boolean" ? notificationAlertsEnabled : true
                }, {
                    key: "participantNotificationAlertsEnabled",
                    value: typeof participantNotificationAlertsEnabled === "boolean" ? participantNotificationAlertsEnabled : false
                }, {
                    key: "debug",
                    value: typeof debug === "boolean" ? debug : false
                }, {
                    key: "participantId",
                    value: participantId || ""
                }, {
                    key: "layoutPriority",
                    value: layoutPriority || ""
                }, {
                    key: "layoutGridSize",
                    value: layoutGridSize || "0"
                }, {
                    key: "hideLocalParticipant",
                    value: typeof hideLocalParticipant === "boolean" ? hideLocalParticipant ? "true" : "false" : "false"
                }, {
                    key: "alwaysShowOverlay",
                    value: typeof alwaysShowOverlay === "boolean" ? alwaysShowOverlay ? "true" : "false" : "false"
                }, {
                    key: "sideStackSize",
                    value: sideStackSize
                }, {
                    key: "reduceEdgeSpacing",
                    value: typeof reduceEdgeSpacing === "boolean" ? reduceEdgeSpacing ? "true" : "false" : "false"
                }, {
                    key: "isRecorder",
                    value: typeof isRecorder === "boolean" ? isRecorder ? "true" : "false" : "false"
                }, {
                    key: "maintainVideoAspectRatio",
                    value: typeof maintainVideoAspectRatio === "boolean" ? maintainVideoAspectRatio ? "true" : "false" : "true"
                }, {
                    key: "maintainLandscapeVideoAspectRatio",
                    value: typeof maintainLandscapeVideoAspectRatio === "boolean" ? maintainLandscapeVideoAspectRatio ? "true" : "false" : "false"
                }, {
                    key: "networkBarEnabled",
                    value: typeof networkBarEnabled === "boolean" ? networkBarEnabled ? "true" : "false" : "true"
                }, {
                    key: "leftScreenRejoinButtonEnabled",
                    value: typeof leftScreenRejoinButtonEnabled === "boolean" ? leftScreenRejoinButtonEnabled ? "true" : "false" : "true"
                }, {
                    key: "joinWithoutUserInteraction",
                    value: typeof joinWithoutUserInteraction === "boolean" ? joinWithoutUserInteraction ? "true" : "false" : "false"
                }, {
                    key: "rawUserAgent",
                    value: rawUserAgent || ""
                }, {
                    key: "meetingLayoutTopic",
                    value: meetingLayoutTopic || ""
                }, {
                    key: "canChangeLayout",
                    value: typeof canChangeLayout === "boolean" ? canChangeLayout ? "true" : "false" : "false"
                }, {
                    key: "participantCanToggleLivestream",
                    value: typeof participantCanToggleLivestream === "boolean" ? participantCanToggleLivestream ? "true" : "false" : "false"
                }, {
                    key: "hlsEnabled",
                    value: hlsEnabled ? "true" : "false"
                }, {
                    key: "autoStartHls",
                    value: autoStartHls ? "true" : "false"
                }, {
                    key: "participantCanToggleHls",
                    value: typeof participantCanToggleHls === "boolean" ? participantCanToggleHls ? "true" : "false" : "false"
                }, {
                    key: "hlsPlayerControlsVisible",
                    value: typeof hlsPlayerControlsVisible === "boolean" ? hlsPlayerControlsVisible ? "true" : "false" : "false"
                }, {
                    key: "hlsTheme",
                    value: hlsTheme || "DEFAULT"
                }, {
                    key: "waitingScreenImageUrl",
                    value: waitingScreenImageUrl || ""
                }, {
                    key: "waitingScreenText",
                    value: waitingScreenText || ""
                }, {
                    key: "cameraResolution",
                    value: cameraResolution || "h360p_w640p"
                }, {
                    key: "cameraId",
                    value: cameraId || ""
                }, {
                    key: "cameraOptimizationMode",
                    value: cameraOptimizationMode || "motion"
                }, {
                    key: "cameraMultiStream",
                    value: typeof cameraMultiStream === "boolean" ? cameraMultiStream ? "true" : "false" : "true"
                }, {
                    key: "screenShareResolution",
                    value: screenShareResolution || "h720p_15fps"
                }, {
                    key: "screenShareOptimizationMode",
                    value: screenShareOptimizationMode || "motion"
                }, {
                    key: "micQuality",
                    value: micQuality || "speech_standard"
                }, {
                    key: "micId",
                    value: micId || ""
                }, {
                    key: "speakerId",
                    value: speakerId || ""
                }, {
                    key: "participantCanToggleRealtimeTranscription",
                    value: typeof participantCanToggleRealtimeTranscription === "boolean" ? participantCanToggleRealtimeTranscription ? "true" : "false" : false
                }, {
                    key: "realtimeTranscriptionEnabled",
                    value: realtimeTranscriptionEnabled ? "true" : "false"
                }, {
                    key: "realtimeTranscriptionVisible",
                    value: realtimeTranscriptionVisible ? "true" : "false"
                }].map(function (_ref3) {
                    var key = _ref3.key,
                        value = _ref3.value;
                    return key + "=" + encodeURIComponent(value);
                }).join("&");
                var embedBaseUrl = _embedBaseUrl ? _embedBaseUrl : "https://embed.videosdk.live/rtc-js-prebuilt/0.3.43/";
                var prebuiltSrc = embedBaseUrl + "/?" + prebuiltSrcQueryParameters;
                return prebuiltSrc;
            };

            var _this4 = this;

            if (!livestream) livestream = {};
            if (!initPermissions) initPermissions = {};
            if (!joinScreen) joinScreen = {};
            if (!leftScreen) leftScreen = {};
            if (!layout) layout = {};
            if (!recording) recording = {};
            if (!hls) hls = {};
            if (!waitingScreen) waitingScreen = {};
            if (!branding) branding = {};
            if (!videoConfig) videoConfig = {};
            if (!screenShareConfig) screenShareConfig = {};
            if (!audioConfig) audioConfig = {};
            if (!i18n) i18n = {};
            if (!realtimeTranscription) realtimeTranscription = {};
            var _initPermissions = initPermissions,
                askJoin = _initPermissions.askToJoin,
                participantCanToggleOtherWebcam = _initPermissions.toggleParticipantWebcam,
                participantCanToggleOtherMic = _initPermissions.toggleParticipantMic,
                partcipantCanToogleOtherScreenShare = _initPermissions.toggleParticipantScreenshare,
                participantCanToggleOtherMode = _initPermissions.toggleParticipantMode,
                canRemoveOtherParticipant = _initPermissions.removeParticipant,
                participantCanEndMeeting = _initPermissions.endMeeting,
                canDrawOnWhiteboard = _initPermissions.drawOnWhiteboard,
                canToggleWhiteboard = _initPermissions.toggleWhiteboard,
                canToggleVirtualBackground = _initPermissions.toggleVirtualBackground,
                participantCanToggleRecording = _initPermissions.toggleRecording,
                participantCanToggleLivestream = _initPermissions.toggleLivestream,
                participantCanToggleHls = _initPermissions.toggleHls,
                canPin = _initPermissions.pin,
                canChangeLayout = _initPermissions.changeLayout,
                canCreatePoll = _initPermissions.canCreatePoll,
                canToggleParticipantTab = _initPermissions.canToggleParticipantTab,
                participantCanToggleRealtimeTranscription = _initPermissions.toggleRealtimeTranscription;

            if (askJoin) {
                participantCanToggleOtherWebcam = false;
                participantCanToggleOtherMic = false;
                partcipantCanToogleOtherScreenShare = false;
            }

            var _livestream = livestream,
                liveStreamEnabled = _livestream.enabled,
                autoStartLiveStream = _livestream.autoStart,
                liveStreamOutputs = _livestream.outputs,
                liveStreamTheme = _livestream.theme;
            var _waitingScreen = waitingScreen,
                waitingScreenImageUrl = _waitingScreen.imageUrl,
                waitingScreenText = _waitingScreen.text;
            var _joinScreen = joinScreen,
                joinScreenEnabled = _joinScreen.visible,
                joinScreenMeetingUrl = _joinScreen.meetingUrl,
                joinScreenTitle = _joinScreen.title;
            var _leftScreen = leftScreen,
                leftScreenActionButton = _leftScreen.actionButton,
                leftScreenRejoinButtonEnabled = _leftScreen.rejoinButtonEnabled;
            leftScreenActionButton = leftScreenActionButton || {};
            var _leftScreenActionButt = leftScreenActionButton,
                leftScreenActionButtonLabel = _leftScreenActionButt.label,
                leftScreenActionButtonHref = _leftScreenActionButt.href;
            var _layout = layout,
                layoutType = _layout.type,
                layoutPriority = _layout.priority,
                layoutGridSize = _layout.gridSize;
            var _recording = recording,
                recordingEnabled = _recording.enabled,
                recordingWebhookUrl = _recording.webhookUrl,
                recordingAWSDirPath = _recording.awsDirPath,
                autoStartRecording = _recording.autoStart,
                recordingTheme = _recording.theme;
            var _hls = hls,
                hlsEnabled = _hls.enabled,
                autoStartHls = _hls.autoStart,
                hlsPlayerControlsVisible = _hls.playerControlsVisible,
                hlsTheme = _hls.theme;
            var _branding = branding,
                brandingEnabled = _branding.enabled,
                brandLogoURL = _branding.logoURL,
                brandName = _branding.name,
                poweredBy = _branding.poweredBy;
            var _videoConfig = videoConfig,
                cameraId = _videoConfig.cameraId,
                cameraResolution = _videoConfig.resolution,
                cameraOptimizationMode = _videoConfig.optimizationMode,
                cameraMultiStream = _videoConfig.multiStream;
            var _screenShareConfig = screenShareConfig,
                screenShareResolution = _screenShareConfig.resolution,
                screenShareOptimizationMode = _screenShareConfig.optimizationMode;
            var _i18n = i18n,
                language = _i18n.lang;
            var _audioConfig = audioConfig,
                micQuality = _audioConfig.quality,
                micId = _audioConfig.micId,
                speakerId = _audioConfig.speakerId;
            var _realtimeTranscriptio = realtimeTranscription,
                realtimeTranscriptionEnabled = _realtimeTranscriptio.enabled,
                realtimeTranscriptionVisible = _realtimeTranscriptio.visible;

            if (!token && !apiKey) {
                throw new Error("Any one of \"token\" or \"apiKey\" must be provided.");
            }

            var _token2 = token;
            return Promise.resolve(_token2 ? _temp5(_token2) : Promise.resolve(_this4.fetchToken({
                apiKey: apiKey,
                askJoin: askJoin,
                participantCanToggleOtherWebcam: participantCanToggleOtherWebcam,
                participantCanToggleOtherMic: participantCanToggleOtherMic,
                partcipantCanToogleOtherScreenShare: partcipantCanToogleOtherScreenShare,
                apiBaseUrl: apiBaseUrl
            })).then(_temp5));
        } catch (e) {
            return Promise.reject(e);
        }
    };

    return VideoSDKMeeting;
}();
//# sourceMappingURL=index.js.map
