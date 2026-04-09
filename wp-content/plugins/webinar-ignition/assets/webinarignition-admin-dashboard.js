(function ($) {



	$(document).on("ready", function () {

		// js code here
		let wp_nonce = WEBINARIGNITION.nonce;
		let webinar_id = "";
		var $ar_settings = $("#ar-settings");
		var $form_builder = $("#wi-form-builder");
		var webinarignitionTranslations = WEBINARIGNITION.translations;
		if (WEBINARIGNITION.hasOwnProperty("webinar")) {
			webinar_id = WEBINARIGNITION.webinar.id;
		}
		
		var $date_change_message = '';
		var save_past_message = '';
		var save_past_message_new = '';

		if(webinarignitionTranslations){
			 save_past_message = webinarignitionTranslations.save_past_message;
			 save_past_message_new = webinarignitionTranslations.save_past_message_new;
			
			$date_change_message = `<p id="date-change-message" style="color: black; margin-top: 5px;">`+webinarignitionTranslations.future_date_message_line_1+`
			<br>`+webinarignitionTranslations.future_date_message_line_2+`<br>
			`+webinarignitionTranslations.future_date_message_line_3+`<br>
			`+webinarignitionTranslations.future_date_message_line_4+`<br>
			`+webinarignitionTranslations.future_date_message_line_5+`<br>
			`+webinarignitionTranslations.future_date_message_line_6+`<br>
			<button id="notify-no" style="margin-right: 2px;">`+webinarignitionTranslations.future_date_NO_text+`</button> | <button id="notify-yes" style="margin-left: 2px;">`+webinarignitionTranslations.future_date_Yes_text+`</button>
			</p>`;
		}

		$('input[id^="additional-autoaction__cta_position__"]').on(
			"change",
			function () {
				// Get the current value of the input field
				var ctaPositionValue = $(this).val();

				// Check the value and toggle the display of .cta_alignement_new accordingly
				if (ctaPositionValue === "overlay") {
					$(".cta_alignement_new").css("display", "block"); // Show the element
					$(".dashboard-cta-width-cont").css("display", "block");
					$(".default-dashboard-cta-transparency-cont").css("display", "block");
				} else {
					$(".cta_alignement_new").css("display", "none"); // Hide the element
					$(".dashboard-cta-width-cont").css("display", "none");
					$(".default-dashboard-cta-transparency-cont").css("display", "none");
				}
			}
		);

		$("#cta_position, #cta_position_additional").on("change", function () {
			// Get the current value of the input field
			var ctaPositionValue = $(this).val();
			if (ctaPositionValue === "overlay") {
				$(".default_cta_alignement_new").css("display", "block"); // Show the element
				$(".default-dashboard-cta-width-cont").css("display", "block");
				$(".default-dashboard-cta-transparency-cont").css("display", "block");
			} else {
				$(".default-dashboard-cta-width-cont").css("display", "none");
				$(".default-dashboard-cta-transparency-cont").css("display", "none");
				$(".default_cta_alignement_new").css("display", "none");
			}
		});

		$(".helper").tooltip();
		function checkIffuturewebinar() {
			const currentDate = $("input[name='webinar_date_submit']").val();
			const currentTime = $("input[name='webinar_start_time_submit']").val();
			const currentTimezone = $("#webinar_timezone").val();

			// Send data to WordPress via AJAX
			return $.ajax({
				url: ajaxurl, // WordPress AJAX URL
				type: 'POST',
				data: {
					action: 'check_webinar_time_new',
					new_date: currentDate,
					new_time: currentTime,
					new_timezone: currentTimezone,
					wp_nonce : wp_nonce

				}
			}).then(function(response) {
				console.log('date response');
				console.log(response);
				return response === 'true';
			});
		}
		// Create NEW Webinar ::
		$("#createnewapp").on("click", function () {
			$("#mWrapper .invalid").removeClass("invalid");

			var $appname = $("#appname").val(),
				$cloneapp = $("#cloneapp").val(),
				$applang = $("#applang").val(),
				webinar_date = $("#webinar_date").val(),
				settings_language = $("#settings_language").val();
		
			if (settings_language == "yes") {
				settings_language = $applang;
			} else {
				settings_language = $("#site_default_language").val();
			}
		
			var webinar_start_time;
			var webinar_start_duration;
			if (webinar_date != "AUTO") {
				webinar_date = $('input[name="webinar_date_submit"]').val();
				webinar_start_time = $('input[name="webinar_start_time_submit"]').val();
				webinar_start_duration = $('input[name="webinar_start_duration"]').val();
			}
		
			var webinar_desc = $("#webinar_desc").val(),
				webinar_host = $("#webinar_host").val(),
				date_format = $("input[name='date_format']:checked").val(),
				date_format_custom = $("input[name='date_format_custom']").val(),
				date_format_custom_new = $("input[name='date_format_custom_new']:checked").val(),
				time_format = $("input[name='time_format']:checked").val(),
				wi_show_day = $("input[name='wi_show_day']").is(":checked"),
				day_string = $("input[name='day_string']:checked").val(),
				webinar_timezone_field = $("#webinar_timezone").val();
		
			if ($appname === "") {
				$("#appname").addClass("invalid");
			}
			if($("#cloneapp").val() === "new") {
				if (webinar_timezone_field === "") {
					$("#webinar_timezone").addClass("invalid");
				}
			}
			if ($("#webinar_date").is(":visible") && webinar_date === "") {
				$("#webinar_date").addClass("invalid");
			}
			if ($("#webinar_desc").is(":visible") && webinar_desc === "") {
				$("#webinar_desc").addClass("invalid");
			}
			if ($("#webinar_host").is(":visible") && webinar_host === "") {
				$("#webinar_host").addClass("invalid");
			}
		
			if ($("#mWrapper .invalid").length) {
				return false;
			}
		
			if ($("#cloneapp").val() === "new") {
				// Asynchronous check
				checkIffuturewebinar().then(function (isMovedForward) {
					if (!isMovedForward) {
						alert(save_past_message_new);
						return; // 
					}
		
					// Now continue with submission (inside the promise)
					$("#createnewappBTN").html(WEBINARIGNITION.translations.saving);
		
					var data = {
						action: "webinarignition_create",
						security: wp_nonce,
						appname: $appname,
						cloneapp: $cloneapp,
						applang: $applang,
						webinar_desc: webinar_desc,
						webinar_host: webinar_host,
						webinar_date: webinar_date,
						webinar_start_time: webinar_start_time,
						webinar_start_duration: webinar_start_duration,
						webinar_timezone: $("#webinar_timezone").val(),
						importcode: $("#importcode").val(),
						date_format: date_format,
						date_format_custom_new: date_format_custom_new,
						date_format_custom: date_format_custom,
						time_format: time_format,
						settings_language: settings_language,
						wi_show_day: wi_show_day,
						day_string: day_string,
					};
		
					$.post(ajaxurl, data, function (response_data) {
						window.location = WEBINARIGNITION.url.dashboard + response_data;
					});
				});
		
				return false; // Prevent form submission immediately; handle it in .then()
			}
		
			// Fallback for non-cloneapp="new"
			$("#createnewappBTN").html(WEBINARIGNITION.translations.saving);
			var data = {
				action: "webinarignition_create",
				security: wp_nonce,
				appname: $appname,
				cloneapp: $cloneapp,
				applang: $applang,
				webinar_desc: webinar_desc,
				webinar_host: webinar_host,
				webinar_date: webinar_date,
				webinar_start_time: webinar_start_time,
				webinar_timezone: $("#webinar_timezone").val(),
				importcode: $("#importcode").val(),
				date_format: date_format,
				date_format_custom_new: date_format_custom_new,
				date_format_custom: date_format_custom,
				time_format: time_format,
				settings_language: settings_language,
				wi_show_day: wi_show_day,
				day_string: day_string,
			};
		
			$.post(ajaxurl, data, function (response_data) {
				window.location = WEBINARIGNITION.url.dashboard + response_data;
			});
		
			return false;
		});
		


		const wiStorageUtil = {
			set: (key, value) => {
			  try {
				localStorage.setItem(key, JSON.stringify(value));
			  } catch (e) {
				console.error(`Error setting ${key} in localStorage:`, e);
			  }
			},
			get: (key) => {
			  try {
				const value = localStorage.getItem(key);
				return value ? JSON.parse(value) : null;
			  } catch (e) {
				console.error(`Error getting ${key} from localStorage:`, e);
				return null;
			  }
			},
			remove: (key) => {
			  try {
				localStorage.removeItem(key);
			  } catch (e) {
				console.error(`Error removing ${key} from localStorage:`, e);
			  }
			}
		  };



		// Populate AR fields

		$(".arSplit").on("click", function (event) {
			event.preventDefault();
			webinarIgnition_ar_extract_fields();
		});

		function webinarIgnition_ar_extract_fields() {
			if ($("#ar_code").prop("disabled")) return;
			$("#ar_code").prop("disabled", true);
			$.post(
				ajaxurl,
				{
					action: "webinarIgnition_ar_extract_fields",
					security: wp_nonce,
					form_data: $("#ar_code").val(),
				},
				function (data) {
					$("#ar_code").prop("disabled", false);
					if (data) {
						$("#ar_url").val(data.form_action);
						for (i in data.form_fields) {
							$("#" + i).val(data.form_fields[i].name || data.form_fields[i]);
						}
						$("#ar_integration_status")
							.show()
							.find(".detected_service")
							.text(data.service);
					}
				},
				"json"
			);
		}

		// Delete Campaign

		$("#deleteCampaign").on("click", function () {
			webinarignition_confirmation($(this));

			return false;
		});

		function webinarignition_confirmation($obj) {
			if (confirm(WEBINARIGNITION.translations.delete_campaign_confirm)) {
				$.post(
					ajaxurl,
					{
						action: "webinarignition_delete_campaign",
						id: webinar_id,
						security: wp_nonce,
					},
					function () {
						window.location = WEBINARIGNITION.url.admin_page;
					}
				);
			}
		}

			// DELETE all LEAD
			jQuery(document).ready(function($) {
				$("#live_delete").on("click", function (e) {
					e.preventDefault(); 
					webinar_id = $('input[name="webinarignition_webinar_id"]').val();
					$.ajax({
						url: ajaxurl, 
						type: 'POST',
						data: {
							action: 'webinarignition_all_lead_delete', 
							id: webinar_id
						},
						success: function(response) {
							if(response.success){
								alert('leads deleted successfully');
							window.location.reload();
							}
						},
						error: function(xhr, status, error) {
							alert('Error: ' + error);
						}
					});
			
					return false;
				});
			});
			jQuery(document).ready(function($) {
				$('#csv-mapping-form').on('submit', function(e) {
					e.preventDefault();
			
					const data = $(this).serialize();
			
					$.post(ajaxurl, {
						action: 'save_csv_mapping',
						nonce: wp_nonce,
						data: data
					}, function(response) {
						$('#csv-mapping-response').html('<div class="notice notice-success"><p>' + response.message + '</p></div>');
					});
				});
			});
			$('#csv-upload-form').on('submit', function(e) {
				e.preventDefault();
				console.log('submit');
			
				var formData = new FormData(this);
				formData.append('action', 'reh_wi_handle_csv_preview');
				formData.append('security', window.wiRegJS.ajax_nonce);
				formData.append('id', window.webinarId);
			
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						if (response.success) {
							const headers = response.data.headers;
							const filePath = response.data.file;
							const previewRow = response.data.preview_row;
					
							showCsvMappingUI(headers, filePath, previewRow);
						} else {
							alert(response.data.message || 'Failed to preview CSV.');
						}
					},
					error: function(error) {
						console.log(error);
					}
				});
			});
			function showCsvMappingUI(headers, filePath, previewRow) {
				headers.unshift('');
				previewRow.unshift('');
				const requiredFields = WEBINARIGNITION.webinar_map_labels;
				let html = '<table><thead><tr><th>Field</th><th>CSV Column</th><th>First Row Value</th></tr></thead><tbody>';
			
				requiredFields.forEach(field => {
					html += `<tr>
						<td><strong>${field}</strong></td>
						<td>
							<select name="map[${field}]">` +
								headers.map((h, i) => {
									const isSelected = h === field ? 'selected' : '';
									return `<option value="${h}" ${isSelected}>${h}</option>`;
								}).join('') +
							`</select>
						</td>
						<td class="preview-cell" data-field="${field}"></td>
					</tr>`;
				});
			
				html += `</tbody></table><br>
				<span id="confirm-mapping" style="
					display: inline-block;
					padding: 10px 20px;
					background-color: #0073aa;
					color: #fff;
					border-radius: 4px;
					cursor: pointer;
					font-weight: bold;
					text-align: center;
				">Upload with Mapping</span>`;				$('#mapping-container').html(html).show();
			
				// Populate preview column on change
				$('#mapping-container select').on('change', function () {
					const selectedHeader = $(this).val();
					const previewIndex = headers.indexOf(selectedHeader);
					const previewValue = previewRow[previewIndex] || '';
			
					const fieldKey = $(this).attr('name').match(/\[(.*?)\]/)[1];
					$(`.preview-cell[data-field="${fieldKey}"]`).text(previewValue);
				});
			
				// Trigger change once to populate defaults
				$('#mapping-container select').trigger('change');
			
				// Submit button logic
				$('#confirm-mapping').on('click', function () {
					const mapping = {};
					$('#mapping-container select').each(function () {
						const field = $(this).attr('name').match(/\[(.*?)\]/)[1];
						mapping[field] = $(this).val();
					});
					console.log('mapping:', mapping);
					uploadCsvWithMapping(filePath, mapping);
				});
			}
			
			function uploadCsvWithMapping(filePath, mapping) {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'reh_wi_handle_csv_upload_mapped',
						security: window.wiRegJS.ajax_nonce,
						id:$('input[name="webinarignition_webinar_id"]').val(),
						mapping: mapping,
						file: filePath
					},
					success: function(response) {
						if (response.success) {
							alert('CSV imported successfully.');
							window.location.reload();
						} else {
							alert(response.data || 'Failed to import CSV.');
						}
					},
					error: function(err) {
						console.log(err);
					}
				});
			}
			// DELETE LEAD
			$(".delete_lead").on("click", function () {
				$ID = $(this).attr("lead_id");
				webinar_id = $('input[name="webinarignition_webinar_id"]').val();
				webinarignition_confirmation2($ID, webinar_id );
	
				return false;
			});
	
			function webinarignition_confirmation2($LEAD, $webinar_id) {
				var answer = confirm(WEBINARIGNITION.translations.delete_lead_confirm);
				if (answer) {
					var data = {
						action: "webinarignition_delete_lead",
						id: "" + $LEAD + "",
						webinar_id: "" + $webinar_id + "",
						security: wp_nonce,
					};
	
					$.post(ajaxurl, data, function (results) {
						$("#table_lead_" + $LEAD).fadeOut("fast");
					});
				} else {
				}
			}

		// Delete Campaign

		$("#resetStats").on("click", function () {
			webinarignition_confirmation44();

			return false;
		});

		function webinarignition_confirmation44() {
			var answer = confirm(
				WEBINARIGNITION.translations.reset_campaign_stats_confirm
			);
			if (answer) {
				var data = {
					action: "webinarignition_reset_stats",
					security: wp_nonce,
					id: webinar_id,
				};

				$.post(ajaxurl, data, function (results) {
					window.location = WEBINARIGNITION.url.page_dashboard + webinar_id;
				});
			} else {
			}
		}

		// Image Add Media Btns

		$photoURLSelected = "";
		$photoWPEditorCheck = "";

		$(".launch_media_lib").on("click", function () {
			$photoURLSelected = $(this).attr("photoBox");

			tb_show("Test", "media-upload.php?type=image&TB_iframe=true");

			return false;
		});

		// Image Option Selector

		$(".dub_select_image").on("click", function () {
			// Get Data
			$ID = $(this).attr("dsID");
			$Data = $(this).attr("dsData");
			// Set Data
			$("#" + $ID + "").val($Data);
			$("#" + $ID + "").trigger("change");
			// Set visible indicator
			$(".ds_" + $ID).removeClass("dub_select_image_selected");
			$(this).addClass("dub_select_image_selected");
			return false;
		});
		function generate_ar_settings() {
			$ar_settings.html("");
			$("<input>").attr("type", "hidden").attr("name", "ar_url").val($("#ar_url").val()).appendTo($ar_settings);
			$("<input>").attr("type", "hidden").attr("name", "ar_method").val($("#ar_method").val()).appendTo($ar_settings);

			$form_builder.find(".wi-form-fieldblock").each(function (elem) {
				var ar_mapping = $(this).find(".field__ar-mapping").val(),
					ar_field_name = $(this).find(".field__ar-name").val(),
					label_field_name = $(this).find(".field__label-name").val(),
					label = $(this).find(".field__label").val(),
					isRequired = $(this).find(".required_ar").is(":checked"),
					// multi col code
					// isShowInNewRow = $(this).find(".new_row_ar").is(":checked"),
					options = "";

				$("<input>").attr("type", "hidden").attr("name", "ar_fields_order[]").val(ar_field_name).appendTo($ar_settings);
				$("<input>").attr("type", "hidden").attr("name", ar_field_name).val(ar_mapping).appendTo($ar_settings);
				$("<input>").attr("type", "hidden").attr("name", label_field_name).val(label).appendTo($ar_settings);

				if ($(this).find(".field__options").length) {
					options = $(this).find(".field__options").val();
					$("<textarea>").attr("name", label_field_name.replace("lp_optin_custom", "lp_optin_custom_select")).val(options).appendTo($ar_settings);
				}

				if (isRequired) {
					$("<input>").attr("type", "hidden").attr("name", "ar_required_fields[]").val(ar_field_name).appendTo($ar_settings);
				}
				// multi col code
				// if (isShowInNewRow) {
				// 	$("<input>").attr("type", "hidden").attr("name", "ar_new_row_fields[]").val(ar_field_name).appendTo($ar_settings);
				// }

			});

			var hidden_fields = "";
			$("#wi-form-hidden-fields")
				.children(".field-group")
				.each(function () {
					var value = $(this).find(".fieldblock__value").val(),
						name = $(this).find(".fieldblock__name").val();

					hidden_fields += $("<input>").attr("type", "hidden").attr("name", name).val(value)[0].outerHTML;
				});
			$("<input>").attr("type", "hidden").attr("name", "ar_hidden").val(hidden_fields).appendTo($ar_settings);
		}
		webinarignition_pre_save = function () {
			generate_ar_settings();
		};

		// --------------------------------------------------------------------------------------
		// fix :: global save
		// --------------------------------------------------------------------------------------
		window.webinarignition_saveIt = function (cbf) {
			webinarignition_pre_save();


			// Loop Through all WP Editors
			$(".wp-editor-wrap")
				.each(function () {
					var editorId = this.id; // get ID wp-ID-wrap
					editorId = editorId.replace("wp-", ""); // replace pre-fix
					editorId = editorId.replace("-wrap", ""); // replace post-fix

					if ($("#wp-" + editorId + "-wrap").hasClass("tmce-active")) {
						// on Visual State
						var content = tinyMCE.get(editorId).getContent();
						$("#" + editorId).val(content);
					} else {
						// on HTML state
						var content = $("#" + editorId).val();
					}
				})
				.promise()
				.done(function () {
					// on complete

					// fix :: YouTube video settings
					var videoSettings = ["webinar_iframe_source", "webinar_live_video"];
					var editAppElement = document.getElementById("editApp");

					videoSettings.forEach(function (setting) {
						if (typeof editAppElement[setting] !== "undefined") {
							var videoElement = editAppElement[setting];

							var htmlContent = videoElement.value;

							if (htmlContent.indexOf("youtube") > 0 && htmlContent.indexOf("[video") < 0) {
								var iframeObject = null; // iframe object
								var divElement = document.createElement("div"); // div element
								var url = null; // string URL
								var urlParams = { rel: 0, autoplay: 1, start: 0 }; // add items to url
								var item = null; // item variable
								var addItemText = null; // add item text
								var delimiter = null; // url variable delimiter

								divElement.innerHTML = htmlContent;

								iframeObject = divElement.getElementsByTagName("iframe")[0];
								url = iframeObject.src;

								Object.keys(urlParams).forEach(function (item) {
									if (url.indexOf(item) < 0) {
										delimiter = url.indexOf("?") < 0 ? "?" : "&";
										addItemText = delimiter + item + "=";

										url += addItemText + urlParams[item];
									}
								});

								videoElement.value = htmlContent.split(iframeObject.src).join(url);
							}
						}
					});

					/**
					 * its important to not include select elements inside #additional_auto_time_template div
					 * because each time when user saves if we will not add the .not it will save an additional entry of this template
					 * because with out .not jquery works even on our templates.
					 */
					
					var formData = $("#editApp")
					.find("select, input, textarea") // Find all form elements
					.not("#additional_auto_time_template select") // Exclude select elements inside #additional_auto_time_template div
					.serializeArray();
					var webinarStatus = $("#webinar_status").prop("checked")
						? "published"
						: "draft";
					formData.push({ name: "webinar_status", value: webinarStatus });

					$.post(ajaxurl, formData, function (data) {
						// fix :: dirty-forms :: sync (clean)
						$(function () {
							$("form").areYouSure();
							$("form.dirty-check").areYouSure();
							$("form").areYouSure({
								message: WEBINARIGNITION.translations.changes_not_saved_warning,
							});
						});

						if (typeof cbf === "function") {
							cbf(data);
						} else {
							window.onbeforeunload = null;
							$(window).off("beforeunload");
							window.onbeforeunload = function () {
								return null; // return null to avoid pop up
							};

							location.reload();
							/**
							 * After saving when reload the page add an identifier 
							 * that will help us to notice that user have clicked on the save button on webinar dashboard page.
							 */
							const currentUrl = new URL(window.location.href);
							currentUrl.searchParams.set('webinar_saved', 'true');
							window.location.href = currentUrl.toString();
						}
					});
				});
		};

		// --------------------------------------------------------------------------------------
		// Save Parts ::

		var nonce = window.WEBINARIGNITION.nonce;
		var ajax_url = window.WEBINARIGNITION.ajax_url;
		var webinar = window.WEBINARIGNITION.webinar_record;
		$( '.saveIt' ).on( 'click', function( event ) {
			event.preventDefault();
			if( $( this ).hasClass( 'disabled-date-time' ) ) {
				alert(save_past_message);
			}else{
				var save_button = $( this );
				webinarignition_saveIt(); 
			}
		});

		// Tabs For Editing App
		$(".editItem").on("click", function () {
			$tab_id = $(this).attr("tab");

			wi_showTab($tab_id);
			/**
			 * Save the active tab information in localStorage
			 * webinarignition own wrapper function for centralize error handling, key management, and serialization logic. 
			 */
			if(isLocalStorageAvailable()){
				wiStorageUtil.set("wi_LastOpenedTab", $tab_id);
				wiStorageUtil.remove("wi_lastOpenedSection")
			}
				
			return false;
		});

		function wi_showTab(tabId){
			$(".editItem").removeClass("editSelected");
			$(`[tab="${tabId}"]`).addClass('editSelected');
			$(".tabber").hide();
			$("#" + tabId + "").show();
		}

		const wi_clearUrlParam = (param) =>{
			 // Create a URL object from the current URL
			var url = new URL(window.location.href);

			// Check if the "webinar_saved" parameter exists
			if(url.searchParams.has(`${param}`)){
				// Remove the parameter
				url.searchParams.delete(`${param}`);

				// Update the URL in the browser's address bar without reloading the page
				window.history.replaceState({}, document.title, url.toString());
 			 }
		}
		const wi_ShowLastOpenedTab = () => {
			var urlParams = new URLSearchParams(window.location.search);

			if (urlParams.get("webinar_saved") === "true") {
				var lastOpenedTab = wiStorageUtil.get("wi_LastOpenedTab");
				if (lastOpenedTab) {
					wi_showTab(lastOpenedTab);
					wi_lastOpenedSection();
					wi_clearUrlParam("webinar_saved");
				}
			}else{
				$('[tab="tab1"]').addClass('editSelected');
			}
		};
		wi_ShowLastOpenedTab();



		/**
		 * Date And Time Preview Tabs JavaScript
		 */
			let wi_activeDateTimePreviewTab = null;

			function wi_showDateAndTimePreview(wi_tabNumber) {
				let wi_newTab = $('#wi_tab' + wi_tabNumber);

				if (wi_activeDateTimePreviewTab === wi_newTab[0]) return;

				if (wi_activeDateTimePreviewTab) {
					$(wi_activeDateTimePreviewTab).slideUp(500, function () {
						wi_activeDateTimePreviewTab = wi_newTab[0];
						wi_slideDateAndTimePreviewDown(wi_newTab);
					});
				} else {
					wi_activeDateTimePreviewTab = wi_newTab[0];
					wi_slideDateAndTimePreviewDown(wi_newTab);
				}
			}

			function wi_slideDateAndTimePreviewDown(element) {
				$(element).slideDown(500);
			}

			$('.wi_tab').click(function () {
				let wi_tabNumber = $(this).data('tab');
				wi_showDateAndTimePreview(wi_tabNumber);
			});





		
		function isLocalStorageAvailable() {
			try {
			  const testKey = '__storage_test__';
			  localStorage.setItem(testKey, testKey);
			  localStorage.removeItem(testKey);
			  return true;
			} catch (e) {
			  return false;
			}
		}


		function phpToPickadate(str) {
			// First convert to Moment format using existing phpToMoment function
			let momentFormat = phpToMoment(str);
			
			// Then convert Moment format to Pickadate format
			let replacements = {
				'MMMM': 'mmmm',  // Full month name
				'MMM': 'mmm',    // Short month name
				'MM': 'mm',      // Month with leading zero
				'M': 'm',        // Month without leading zero
				'DD': 'dd',      // Day with leading zero
				'D': 'd',        // Day without leading zero
				'YYYY': 'yyyy',  // Full year
				'YY': 'yy'       // Short year
			};
		
			// Sort keys by length (longest first) to avoid partial replacements
			let sortedKeys = Object.keys(replacements).sort((a, b) => b.length - a.length);
			
			sortedKeys.forEach(key => {
				momentFormat = momentFormat.split(key).join(replacements[key]);
			});
		
			return momentFormat;
		}
		jQuery(document).ready(function($) {
			// Store original values on page load
			let originalValues = {
				date: $("[name='webinar_date']").val(),
				time: $("[name='webinar_start_time_submit']").val(),
				timezone: $("[name='webinar_timezone']").val()
			};
		
			// Function to check if webinar was moved forward via AJAX
			function checkIfMovedForward() {
				console.log(originalValues);
				const currentDate = $("[name='webinar_date_submit']").val();
				const currentTime = $("[name='webinar_start_time_submit']").val();
				const currentTimezone = $("[name='webinar_timezone']").val();
				const webinar_id = $("[name='wi_webinar_id_resend_mail']").val();
	
		
				// Send data to WordPress via AJAX
				return $.ajax({
					url: ajaxurl, // WordPress AJAX URL
					type: 'POST',
					data: {
						action: 'check_webinar_time_change',
						original_date: originalValues.date,
						original_time: originalValues.time,
						original_timezone: originalValues.timezone,
						new_date: currentDate,
						new_time: currentTime,
						webinar_id: webinar_id,
						new_timezone: currentTimezone,
						wp_nonce : wp_nonce

					}
				}).then(function(response) {
					console.log('response');
					console.log(response);
					return response === 'true';
				});
			}
		
			// Event handlers for all three fields
			$("[name='webinar_date'], [name='webinar_start_time'], #webinar_timezone").on('change', function() {
				checkIfMovedForward().then(function(isMovedForward) {
					originalValues = {
						date: $("[name='webinar_date_submit']").val(),
						time: $("[name='webinar_start_time_submit']").val(),
						timezone: $("[name='webinar_timezone']").val()
					};
					if (isMovedForward) {
						let messageContainer = $("#date-change-message");
						$('#saveIt').removeClass('disabled-date-time');

						$(".webinarStatusFirst[data='countdown']").trigger("click");
					
						if (!messageContainer.length) {
							// If the message container doesn't exist, create it
							messageContainer = $("<div id='date-change-message'></div>");
							$("#webinar_timezone").after(messageContainer);
						}
					
						// Update the message if it already exists
						messageContainer.html($date_change_message);
						messageContainer.show();

					}else{
						let messageContainer = $("#date-change-message");
						if (!messageContainer.length) {
							// If the message container doesn't exist, create it
							messageContainer = $("<div id='date-change-message'></div>");
							$("#webinar_timezone").after(messageContainer);
						}
					
						// Update the message if it already exists
						messageContainer.hide();
						$('#saveIt').addClass('disabled-date-time');
					}
					
				}).catch(function(error) {
					console.error('Error checking webinar time:', error);
				});
			});
		});
		
		
		
		$(document).on("change", "#webinar_timezone", function (event) {
			$("#date-change-message").show();
			 // Check if the #notify_current_user field exists
			 if ($("#notify_current_user").length > 0) {

				// Add or update the message below the date field
				// let messageContainer = $("#date-change-message");
				// if (!messageContainer.length) {
				// If the message container doesn't exist, create it
				// 	$("#webinar_timezone").after(
				// 		$date_change_message
				// 	);
				// } else {
				// Update the message if it already exists
				// 	messageContainer.html($date_change_message);
				// }
			}
				
		});

		$(document).on("click", "#notify-no", function (event) {
			event.preventDefault(); // Prevent the default button action (e.g., form submission or redirect)

			// Show a confirmation dialog with "OK" and "Cancel"
			if (confirm("Are you sure you want to proceed?")) {
				
				// User clicked "OK," perform the AJAX call
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: "date_change_on_no",
						security: wp_nonce,
						wi_webinar_id_resend_mail: $("#wi_webinar_id_resend_mail").val(),
					},
					success: function(response) {
						// Handle the successful response from the server
						console.log("Server response:", response);
						$("#date-change-message").hide();
						// Trigger the click event of the "save" button
						$("#saveIt").click(); // Automatically click the save button
						
					},
					error: function(xhr, status, error) {
						// Handle errors
						console.error("Error:", error);
						alert("An error occurred while updating the date.");
					}
				});
			} else {
				// User clicked "Cancel"
				console.log("Action cancelled.");
			}
		});

		function wiBtnLoading(btnId, status = true){
			let icon = `<svg class="wi-loading-icon" fill="currentColor" height="15px" width="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
				viewBox="0 0 214.367 214.367" xml:space="preserve">
				<path d="M202.403,95.22c0,46.312-33.237,85.002-77.109,93.484v25.663l-69.76-40l69.76-40v23.494
					c27.176-7.87,47.109-32.964,47.109-62.642c0-35.962-29.258-65.22-65.22-65.22s-65.22,29.258-65.22,65.22
					c0,9.686,2.068,19.001,6.148,27.688l-27.154,12.754c-5.968-12.707-8.994-26.313-8.994-40.441C11.964,42.716,54.68,0,107.184,0
					S202.403,42.716,202.403,95.22z"/>
				</svg>`;

				if(status) {
					$("#" + btnId)
						.prop("disabled", true)
						.css({
							opacity: 0.6,
							cursor: "not-allowed"
						});
						$("#"+btnId).html(icon + " Sending");
				} else{
					$("#"+btnId).html("Yes");
					$("#" + btnId)
						.prop("disabled", false)
						.css({
							opacity: "initial",
							cursor: "pointer"
						});
				}
			

		}

		$(document).on("click", "#notify-yes", function (event) {
			wiBtnLoading('notify-yes');
			
			event.preventDefault(); // Prevent the default button action (e.g., form submission or redirect)
			$("#notify_current_user").val("yes"); // Set hidden input value to "yes"
			// Get the hidden field value
			$("#wi_webinar_id_resend_mail").val();
		
			// Perform an AJAX call
			$.ajax({
				url: ajaxurl, 
				type: 'POST', 
				data: {
					action: "notification_current_user",
					security: wp_nonce,
					notify_current_user: $("#notify_current_user").val(),
					wi_webinar_date_resent_mail: $("[name='webinar_date_submit']").val(),
					webinar_start_time_submit: $("[name='webinar_start_time_submit']").val(),
					wi_webinar_id_resend_mail: $("#wi_webinar_id_resend_mail").val(),
					wi_webinar_timezone_resend: $("#webinar_timezone").val(),
				}, 
				success: function(response) {
					// Handle the successful response from the server
					$("#date-change-message").hide();
					// Trigger the click event of the "save" button
					wiBtnLoading("notify-yes", false);
					$("#saveIt").click(); // Automatically click the save button
					
				},
				error: function(xhr, status, error) {
					// Handle errors
					wiBtnLoading("notify-yes", false);
					alert("An error occurred while updating the notification preference.");
				}
			});
		});
		if ( $(".dp-date").length ) { 
			// var dateFormat = WEBINARIGNITION.webinar_date_js_format.replace(/^D\s*/, "");
			var dateFormat = $("input[name='date_format_custom']").val();
			if(dateFormat){
				var pickadateFormat = phpToPickadate(dateFormat);
			}
			// Variable to store the previously selected date

			$(".dp-date").pickadate({
				format: pickadateFormat,
				formatSubmit: "mm-dd-yyyy",
				firstDay: 1,
				today: webinarignitionTranslations.today,
				clear: webinarignitionTranslations.clear,
				close: webinarignitionTranslations.close,
				editable: true,
				min: new Date(),
				monthsFull: webinarignitionTranslations.monthsArray,
				weekdaysFull: webinarignitionTranslations.weekdaysFull,
				weekdaysShort: webinarignitionTranslations.weekdaysShort,
				onOpen: function() {
					previous_date = this.get("select");
					previous_d_only = $("input[name='webinar_date_submit']").val();

				  },
				onSet: function () {
					var chosenDate = this.get("select"),
						elementId = this.get("id");
					if (chosenDate) {
						var selectedDate = new Date(chosenDate.obj.getTime());
							if(typeof previous_date !== "undefined" && previous_date) {
								var previous_Date = new Date(previous_date.obj.getTime());
								console.log("Previous Date:", previous_date);
								// Check if the selected date is before the current date
								if (selectedDate < previous_Date) {
									// Show the message for selecting a past date
									$("#date-change-message").html("Please select a webinar start in the future.").show();
									return; // Exit the function to prevent further execution
								}


								// Check if previousDate exists and if the selected date is after it
								if (previous_Date && selectedDate > previous_Date) {
									// Automatically click the ".webinarStatus" button
									$("#date-change-message").show();
									
									// Check if the #notify_current_user field exists
									if ($("#notify_current_user").length > 0) {

										// Add or update the message below the date field
										// let messageContainer = $("#date-change-message");
										// if (!messageContainer.length) {
										// If the message container doesn't exist, create it
										// 	$("#webinar_timezone").after(
										// 		$date_change_message
										// 	);
										// } else {
										// Update the message if it already exists
										// 	messageContainer.html($date_change_message);
										// }
									}
									

									// Add click event listener for the "NO" button
									$(document).on("click", "#notify-no", function (event) {
										event.preventDefault(); // Prevent the default button action
										
									});
									// Save previous_Date to the hidden input field
									if (previous_Date) {
										$("#wi_live_previous_date").val(previous_d_only);
									}
									// Optional: Use SweetAlert for a better popup
									// Swal.fire({
									//     title: "Date Updated!",
									//     text: "The new date is after the previously selected date.",
									//     icon: "info",
									//     confirmButtonText: "OK",
									// });
								}

								// Update the previousDate to the newly selected date
								previous_Date = selectedDate;
							}
					}

					var $picker_1_elem = $("#email_notiff_date_1");

					if ($picker_1_elem.length && elementId === "webinar_date") {
						// day-before
						var $picker_1 = $picker_1_elem.pickadate("picker");
						var dateObject_1 = new Date(chosenDate.obj.getTime());
						dateObject_1.setDate(dateObject_1.getDate() - 1);
						$picker_1.set("select", dateObject_1);

						// date of hour-before
						var $picker_2 = $("#email_notiff_date_2").pickadate("picker");
						$picker_2.set("select", new Date(chosenDate.obj.getTime()));

						// live date
						var $picker_3 = $("#email_notiff_date_3").pickadate("picker");
						$picker_3.set("select", new Date(chosenDate.obj.getTime()));

						

						// date of hour-after
						var $picker_4 = $("#email_notiff_date_4").pickadate("picker");
						$picker_4.set("select", new Date(chosenDate.obj.getTime()));

						// day-after
						var $picker_5 = $("#email_notiff_date_5").pickadate("picker");
						var dateObject_5 = new Date(chosenDate.obj.getTime());
						dateObject_5.setDate(dateObject_5.getDate() + 1);
						$picker_5.set("select", dateObject_5);
					}
				},
			});


		}

		if ( $(".timepicker").length ) {
			var time_format = $(".timepicker").data("time-format");
			$(".timepicker").pickatime({
				format: time_format === "H" ? "H:i" : "h:i A",
				formatSubmit: "hh:i A",
				editable: true,
				interval: 15,
				clear: "",
				onSet: function () {
					var chosenTime = this.get("select"),
						elementId = this.get("id");
					var $picker_1_elem = $("#email_notiff_time_1");

					if ($picker_1_elem.length && elementId === "webinar_start_time") {
						let date = new Date();
						date.setHours(chosenTime.hour, chosenTime.mins);

						//day-before
						var $picker_1 = $("#email_notiff_time_1").pickatime("picker");
						$picker_1.set("select", [chosenTime.hour, chosenTime.mins]);

						//hour-before
						var $picker_2 = $("#email_notiff_time_2").pickatime("picker");
						var $picker_2Date = new Date(date.getTime());
						$picker_2Date.setHours(date.getHours() - 1);
						$picker_2.set("select", [
							$picker_2Date.getHours(),
							$picker_2Date.getMinutes(),
						]);

						//live hour
						var $picker_3 = $("#email_notiff_time_3").pickatime("picker");
						$picker_3.set("select", [chosenTime.hour, chosenTime.mins]);

						//hour-after
						var $picker_4 = $("#email_notiff_time_4").pickatime("picker");
						var $picker_4Date = new Date(date.getTime());
						$picker_4Date.setHours(date.getHours() + 1);
						$picker_4.set("select", [
							$picker_4Date.getHours(),
							$picker_4Date.getMinutes(),
						]);

						//day-after
						var $picker_5 = $("#email_notiff_time_5").pickatime("picker");
						$picker_5.set("select", [chosenTime.hour, chosenTime.mins]);
					}
				},
			});
		}


		// Toggle Edit Section

		$(".editableSectionHeading").on("click", function () {
			if ($(this).hasClass("editableSectionHeadingDASH")) return true;

			$getID = $(this).attr("editSection");
			$("#" + $getID).slideToggle();

			$(this).toggleClass("editableSectionHeading_open");

			$(this)
				.find(".toggleIcon")
				.toggleClass("icon-chevron-up icon-chevron-down");

			// if ( $(this).hasClass('editableSectionHeading_open') ) {
			var section = $(this).attr('editsection');
			if ($(this).hasClass('editableSectionHeading_open') && $('#' + section + ' .optionSelector.userSelected').length > 0) {
				$('#' + section + ' .optionSelector.optionSelectorSelected').trigger('click');
			}

			if(isLocalStorageAvailable)
				wiStorageUtil.set("wi_lastOpenedSection", section);
			return false;
		});

		function wi_lastOpenedSection() {
			// Select the element that has an editsection attribute equal to sectionId
			const sectionId = wiStorageUtil.get("wi_lastOpenedSection");
			if(!isLocalStorageAvailable || !sectionId) return;

			const section = $('[editsection="' + sectionId + '"]');
			if(!section.length) return;
			$("#" + sectionId).slideToggle();
			section.addClass("editableSectionHeading_open");
			// tackle icon
			section
			  .find(".toggleIcon")
			  .toggleClass("icon-chevron-up icon-chevron-down");

			//   $('html, body').animate({
			// 	scrollTop: $(`#${sectionId}`).offset().top - 200
			//   }, 1000);

		  }
		
		var paid_pay_url_clone = $("#paid_pay_url").clone();
		var paid_pay_url_parent = $("#wi_checkout_url_field").find(".inputSection");

		var custom_registration_page_selects = $(
			"#custom_registration_page, #custom_registration_page.inputFieldTemplateSelect"
		).on("change", function (event) {



			/**
			 * this on change function was calling with no reason when select on Protected webinar ID on tab6 
			 * so this statement will prevent to execute this function 
			 */
			if ($('.editNav [tab="tab6"]').hasClass('editSelected')) return;
			



			custom_registration_page_selects.not(this).get(0).selectedIndex =
				this.selectedIndex;

			if ($(this).val() != "") {
				let paid_thank_you_url = $(this)
					.find(":selected")
					.data("paid-thank-you-url");
				if (paid_thank_you_url) {
					$("#tab3 input#paid_thank_you_url").val(paid_thank_you_url);
				}
			} else {
				let paid_thank_you_url = $(
					"#tab9 span#default_paid_thank_you_url"
				).data("url");
				if (paid_thank_you_url) {
					$("#tab3 input#paid_thank_you_url").val(paid_thank_you_url);
				}
			}

			if (
				custom_registration_page_selects
					.not(this)
					.hasClass("inputFieldTemplateSelect")
			) {
				custom_registration_page_selects.not(this).trigger("change");
			}
		});

		// Option Selector
		$(document).on("click", ".optionSelector", function (e) {
			e.preventDefault();
			// class userSelected will point out that user have made a selection 
			$(this).addClass("userSelected"); // Mark as changed
			$getID = $(this).attr("data-id");
			$getVALUE = $(this).attr("data-value");

			var wi_wc_display_field = $("#wi_wc_display_field").data("field-value");
			if (wi_wc_display_field === 0) {
				if ($getVALUE === "woocommerce") {
					paid_pay_url_parent
						.empty()
						.append(
							"<p>" + paid_pay_url_clone.data("message-woocommerce") + "</p>"
						);
				} else {
					paid_pay_url_parent.empty().append(paid_pay_url_clone);
				}
			}

			if ($getVALUE === "paypal") {
				$("#paid_pay_url")
					.attr("placeholder", $("#paid_pay_url").data("url-" + $getVALUE))
					.addClass("paypal_check")
					.trigger("blur");
			} else {
				$("#paid_pay_url")
					.attr("placeholder", $("#paid_pay_url").data("url-" + $getVALUE))
					.removeClass("paypal_check");
			}

			// Set value
			$("#" + $getID)
				.val($getVALUE)
				.trigger("change");

			// Set Selected
			$(".opts-grp-" + $getID).removeClass("optionSelectorSelected");
			$(this).addClass("optionSelectorSelected");

			// Set Icon
			$(".opts-grp-" + $getID)
				.find("i")
				.removeClass("icon-circle");
			$(".opts-grp-" + $getID)
				.find("i")
				.addClass("icon-circle-blank");
			$(this).find("i").addClass("icon-circle");

			// Set for hide / show editable areas
			// $("." + $getID).hide();

			var all_items = $("." + $getID);

			if (all_items.length) {
				all_items.each(function (index) {
					$(this).hide();
				});
			}

			if ($getVALUE === "woocommerce" || $getVALUE === "other") {
				$("#" + $getID + "_" + "paypal").show();
			} else {
				$("#" + $getID + "_" + $getVALUE).show();
			}

			if ($getVALUE === "woocommerce") {
				var visible_items = $("." + $getID + "_paypal_visible").show();
			} else {
				var visible_items = $(
					"." + $getID + "_" + $getVALUE + "_visible"
				).show();
			}

			if (visible_items.length) {
				visible_items.each(function (index) {
					$(this).show();
				});
			}

			return false;
		});

		$(document.body).on('click', '.wi_upload_image_btn', function() {
			var btn = $(this);
			console.log("BUTTON CLICKED: ", btn);
			var container = btn.parents('.inputSection');
			var img_holder = container.find('.input_image_holder');
			var input = container.find('.inputField');
			var delete_btn = container.find('.wi_delete_image_btn');
	
			var custom_uploader = wp.media({
				title: webinarignitionTranslations.wpMediaImgTitle,
				library : {
					// uncomment the next line if you want to attach image to the current post
					// uploadedTo : wp.media.view.settings.post.id,
					type : 'image'
				},
				button: {
					text: webinarignitionTranslations.wpMediaImgButtonText // button label text
				},
				multiple: false // for multiple image selection set to true
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				var url = attachment.url;
	
				img_holder.html('<img src="' + attachment.url + '" />');
				input.val(url);
				delete_btn.show();
			}).open();
		});

		// Option Selectors - On Load
		$(".optionSelector").each(function (index) {
			// Get info
			$getID = $(this).attr("data-id");
			$getVALUE = $(this).attr("data-value");

			// Get Current value
			$getCurrent = $("#" + $getID).val();

			// $("." + $getID).hide();

			var all_items = $("." + $getID);

			if (all_items.length) {
				all_items.each(function (index) {
					$(this).hide();
				});
			}
			$("#" + $getID + "_" + $getCurrent).show();

			var visible_items = $(
				"." + $getID + "_" + $getCurrent + "_visible"
			).show();

			if ("auto_action" === $getID) {
			}

			if (visible_items.length) {
				visible_items.each(function (index) {
					$(this).show();
				});
			}
		});

		$( ".opts-grp-paid_button_type.optionSelector.optionSelectorSelected").trigger("click");

		// Question On Load - Sort Answered - Active
		$(".questionBlock").each(function () {
			$getStatus = $(this).attr("data-q-status");
			$getID = $(this).attr("data-id");

			if ($getStatus == "live") {
				// Its an active question
				$(this).appendTo("#we_active_questions");
			} else {
				// marked as answered
				$("#markReadQ-" + $getID).hide();
				$(this).appendTo("#we_answered_questions");
			}
		});
		$(".webinarStatus").on("click", function () {
			$new_status = $(this).attr("data");
			$old_status = $("#webinar_switch").val();
			if($old_status != $new_status && $new_status) {
				$("#webinar_switch").val($new_status);


				$(".webinarStatus").removeClass("webinarStatusSelected");
				$(this).addClass("webinarStatusSelected");
				$.post(
					ajaxurl,
					{
						action: 'webinarignition_update_admin_webinar_status',
						webinarId: webinar_id,
						security: wp_nonce,
						webinar_switch: $new_status
					},
					function(response) {
						// Success callback
						if(response.success) {
							// Handle success response
							alert('Webinar status updated successfully!');
						}
						console.log(response); // optional: to see the response from the server
					}
				); 
			}
		});
		// Mark Q As Read
		$(".markAsReadQ").on("click", function () {
			$getID = $(this).attr("data-id");

			// make update on POST
			var data = {
				action: "webinarignition_update_question_status",
				security: wp_nonce,
				id: "" + $getID + "",
			};
			$.post(ajaxurl, data, function (results) {
				$("#questionBlock-" + $getID).appendTo("#we_answered_questions");
				$("#markReadQ-" + $getID).hide();
			});

			return false;
		});

		// Delete Question
		$(".deleteQ").on("click", function () {
			$getID = $(this).attr("data-id");

			// make update on POST
			var data = {
				action: "webinarignition_delete_question",
				security: wp_nonce,
				id: "" + $getID + "",
			};
			$.post(ajaxurl, data, function (results) {
				$("#questionBlock-" + $getID).fadeOut("fast");
			});

			return false;
		});

		// LEADS - DASHBOARD
		// $("#leads").dataTable();

		$("#leads_filter")
			.find("input")
			.attr(
				"placeholder",
				WEBINARIGNITION.translations.search_leads_placeholder
			);

		// Creation -- Show / Hide Based On Type
		$("#cloneapp").on("change", function () {
			$data = $(this).val();
			var wi_sll = $("#wi_sll").val();
			var wi_segl = $("#wi_segl").val();

			if ($data == "new") {
				//Live
				$('input[name="day_string"][value="l"]').prop('checked', true);
				$('input[name="day_string"]').trigger('change');
				// show all the bits
				$(
					"#createToggle1, #createToggle2, #createToggle3, #createToggle4, .weCreateRight, .weDashRight, .date_formats, .time_formats, #webinar_language"
				).show();
				$(".importArea").hide();
				if (window.matchMedia("(max-width: 786px)").matches) {
					// Screen size is less than 786px
					$("#formArea").css("flex-direction", "column");
					$(".weCreateRight").css("width", "100%");
				} else {
					// Screen size is greater than 786px
					$("#formArea").css("flex-direction", "row");
					$(".weCreateRight").css("width", "30%");
				}
				$("#formArea").css("gap","15px");
				
				// $(".weCreateLeft").width(530);

				$(".weCreateRight").animate({ marginTop: "0px" }, "fast");

				$(".weCreateTitleIconI").addClass("icon-arrow-right");
				$(".weCreateTitleIconI").removeClass("icon-arrow-down");

				// if (wi_sll == 0) {
				// 	$("#applang").find("option:first-child").prop("selected", true);
				// 	$("#applang").find("option").attr("disabled", true);
				// 	$("#applang").find("option:first-child").prop("disabled", false);

				// 	$("#settings_language")
				// 		.find("option:nth-child(2)")
				// 		.prop("selected", true);
				// 	$("#settings_language").attr("disabled", true);
				// 	$("#applang").trigger("change");
				// 	$("#plan_upgrade_notice_live_webinars").show();
				// }
				// $("#wi-create-new-webinar-btn-wrap").css("marginTop", "800px");

			} else if ($data == "auto") {
				//EG
				// hide time settings...
				$('input[name="day_string"][value="D"]').prop('checked', true);
				$('input[name="day_string"]').trigger('change');

				$("#createToggle1, #createToggle2, #createToggle3, #createToggle4, .importArea").hide();

				$(".weCreateRight, .weDashRight, .date_formats, .time_formats").show();
				if (window.matchMedia("(max-width: 786px)").matches) {
					// Screen size is less than 786px
					$("#formArea").css("flex-direction", "column");
					$(".weCreateRight").css("width", "100%");
				} else {
					// Screen size is greater than 786px
					$("#formArea").css("flex-direction", "row");
					$(".weCreateRight").css("width", "30%");
				}
				$("#formArea").css("gap","15px");

				/**
				 * this width will be given using css not javascript
				 */
				// $(".weCreateLeft").width(530);

				// $(".weCreateRight").css("margin-top", "83px");
				// $(".weCreateRight").animate({ marginTop: "83px" }, "fast");

				$(".weCreateTitleIconI").removeClass("icon-arrow-right");
				$(".weCreateTitleIconI").addClass("icon-arrow-down");

				// $(".weCreateTitleIconI").animate({ marginRight: '-303px' }, 'fast');
				if (wi_segl == 1) {
					$("#applang").find("option").prop("disabled", false);
					$("#settings_language")
						.prop("disabled", false)
						.find("option")
						.prop("disabled", false);
					$("#applang").trigger("change");
					$("#plan_upgrade_notice_live_webinars").hide();
				}
				// $("#wi-create-new-webinar-btn-wrap").css("marginTop", "367px");
			} else if ($data == "import") {

				console.log("user select import");
				$("#formArea").css("flex-direction","column");
				$("#formArea").css("gap","0px");
				// hide side bar and change arrow
				$(
					".weDashRight, .date_formats, .time_formats, #webinar_language"
				).hide();
				$(".weCreateLeft").animate({ width: "100%" }, "fast");
				$(".weCreateTitleIconI").removeClass("icon-arrow-right");
				$(".weCreateTitleIconI").addClass("icon-arrow-down");
				$(".importArea").show();
				 $(".weCreateRight").css("width", "100%");
				// $("#wi-create-new-webinar-btn-wrap").css("marginTop", "0px");

	

			} else {
				// hide side bar and change arrow 
				$(
					".weDashRight, .date_formats, .time_formats, #webinar_language, .importArea"
				).hide();
				$(".weCreateLeft").animate({ width: "100%" }, "fast");

				$(".weCreateTitleIconI").removeClass("icon-arrow-right");
				$(".weCreateTitleIconI").addClass("icon-arrow-down");
				$("#formArea").css("flex-direction","column");
				$("#formArea").css("gap","0px");
				 $(".weCreateRight").css("width", "100%");
				// $("#wi-create-new-webinar-btn-wrap").css("marginTop", "0px");

			}

			return false;
		});

		$("#cloneapp").trigger("change");

		// Timezone -- For User Reference
		var today = new Date();
		var time = today.getHours() + ":" + today.getMinutes();
		$.post(
			ajaxurl,
			{
				action: "webinarignition_ajax_get_localized_time",
				time: time,
				security: wp_nonce,
			},
			function (response) {
				$(".timezoneRefZ").html(response);
			}
		);

		var tz = jstz.determine_timezone();
		var tzname = tz.timezone.olson_tz;
		var tzoffset = tz.timezone.utc_offset;
		$(".timezoneRefZ").text(tzname);

		// Get Timezone & info
		var data = {
			action: "webinarignition_get_local_tz",
			security: wp_nonce,
			tz: "" + tzname + "",
		};
		$.post(ajaxurl, data, function (results) {
			//$(".timezoneRefZ").html(results);
		});

		// Get Timezone & info -- CREATION SET
		var data = {
			action: "webinarignition_get_local_tz_set",
			security: wp_nonce,
			tz: "" + tzname + "",
		};
		$.post(ajaxurl, data, function (results) {
			$(".tzCreate").val(results);
		});

		$(document.body).on("click", "#createAutoTime", function () {
			var btn = $(this);

			var template = $("#additional_auto_time_template").html();
			var container = $("#additional_auto_time_container");

			container.append(template);

			var last = reindex_additional_auto_times();
		});

		$(document.body).on("click", ".deleteAutoTime", function () {
			var btn = $(this);
			var container = btn.parents(".additional_auto_time_item");
			container.remove();

			var last = reindex_additional_auto_times();
		});

		function reindex_additional_auto_times(cb) {
			var containers = $(
				"#additional_auto_time_container .additional_auto_time_item"
			);

			if (containers.length) {
				var last;
				var last_continer;

				containers.each(function (index) {
					var container = $(this);
					last_continer = container;
					var num = index + 1;
					last = num;
					var header = container.find(".inputTitleCopy");
					var header_num = header.find("span.index_holder");
					header_num.text(num + 3);

					var selects = container.find("select.select_auto_time");

					if (selects.length) {
						selects.each(function () {
							var input = $(this);
							var id = input.attr("id");
							var id_array = id.split("__");
							id = id_array[0] + "__" + id_array[1] + "__" + num;
							input.attr("id", id).attr("disabled", false);
						});
					}

					var selects_weekday = container.find("select.select_auto_weekday");

					if (selects_weekday.length) {
						selects_weekday.each(function () {
							var input = $(this);
							var id = input.attr("id");
							var id_array = id.split("__");
							id = id_array[0] + "__" + id_array[1] + "__" + num;
							var name_array = input.attr("name").split("[");
							var name = name_array[0] + "[" + index + "][]";
							input.attr("id", id).attr("name", name).attr("disabled", false);
						});
					}
				});

				return last_continer;
			}
		}

		$(document.body).on(
			"click",
			"#createWebinarTab, #createWebinarQATab, #createWebinarGiveawayTab",
			function () {
				var btn = $(this);
				var template = $("#webinar_tabs_template_container").html();
				var container = $("#webinar_tabs_container");

				make_last_added_webinar_tab_active();

				container.append(template);

				var last = reindex_webinar_tabs();
				last.addClass("auto_action_item_active");

				if (btn.hasClass("shortcode_tab")) {
					var title = btn.data("title");
					var type = btn.data("type");
					var content = btn.data("content");

					var title_input = last.find(".webinar_tabs_name");
					var type_input = last.find(".webinar_tabs_type");
					var content_input = last.find(".webinar_tabs_content");

					title_input.val(title);
					type_input.val(type);

					var editorId = content_input.attr("id");
					content_input.text(content);
					wp.editor.remove(editorId);

					wp.editor.initialize(editorId, {
						tinymce: {
							height: 250,
							teeny: false,
							wpautop: false,
							plugins:
								"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
							toolbar1:
								"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
							toolbar2:
								"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
						},
						quicktags: true,
						mediaButtons: true,
					});

					btn.hide();
				}

				var offset = last.offset();
				var topScroll = offset.top - 40;
				$("html, body").stop().animate({ scrollTop: topScroll }, 500);
			}
		);

		$(document.body).on("click", ".deleteWebinarTab", function () {
			var btn = $(this);
			var container = btn.parents(".additional_auto_action_item");
			var type = container.find(".webinar_tabs_type").val();
			container.remove();

			if (type === "qa_tab") {
				$("#createWebinarQATab").show();
			} else if (type === "giveaway_tab") {
				$("#createWebinarGiveawayTab").show();
			}

			var last = reindex_webinar_tabs();

			if (last) {
				var offset = last.offset();
				var topScroll = offset.top - 40;
				$("html, body").stop().animate({ scrollTop: topScroll }, 500);
			}
		});

		function make_last_added_webinar_tab_active() {
			var items = $("#webinar_tabs_container .webinar_tab_item");

			if (items.length) {
				items.each(function (index) {
					var item = $(this);

					item.removeClass("auto_action_item_active");
				});
			}
		}

		function reindex_webinar_tabs() {
			var containers = $("#webinar_tabs_container .webinar_tab_item");

			if (containers.length) {
				var last;
				var last_continer;

				containers.each(function (index) {
					var container = $(this);
					last_continer = container;
					var num = index + 1;
					last = num;

					var header = container.find(".auto_action_header h4");
					var header_num = header.find("span.index_holder");
					header_num.text(num);

					var webinar_tabs_name = container.find(".webinar_tabs_name");
					var webinar_tabs_type = container.find(".webinar_tabs_type");
					var webinar_tabs_content = container.find(".webinar_tabs_content");

					if (webinar_tabs_name.length) {
						webinar_tabs_name.attr("id", "webinar_tabs_name_" + index);
						webinar_tabs_name.attr("name", "webinar_tabs[" + index + "][name]");
					}

					if (webinar_tabs_type.length) {
						webinar_tabs_type.attr("id", "webinar_tabs_type_" + index);
						webinar_tabs_type.attr("name", "webinar_tabs[" + index + "][type]");
					}

					if (webinar_tabs_content.length) {
						var editorId = "webinar_tabs_content_" + index;
						webinar_tabs_content.attr("id", editorId);
						webinar_tabs_content.attr(
							"name",
							"webinar_tabs[" + index + "][content]"
						);

						wp.editor.remove(editorId);

						wp.editor.initialize(editorId, {
							tinymce: {
								height: 250,
								teeny: false,
								wpautop: false,
								plugins:
									"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
								toolbar1:
									"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
								toolbar2:
									"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
							},
							quicktags: true,
							mediaButtons: true,
						});
					}
				});

				return last_continer;
			}
		}

		$(document.body).on("click", "#createTrackingTag", function () {
			var btn = $(this);
			var template = $(".tracking_tags_template_container").html();

			var container = $("#tracking_tags_container");

			make_last_added_tag_active();

			container.append(template);

			var last = reindex_tracking_tags();
			last.addClass("auto_action_item_active");

			var offset = last.offset();
			var topScroll = offset.top - 40;
			$("html, body").stop().animate({ scrollTop: topScroll }, 500);
		});

		$(document.body).on("click", ".cloneTrackingTag", function () {
			var btn = $(this);
			var cloned = btn.parents(".tracking_tag_item");

			var tracking_tags_time = cloned.find(".tracking_tags_time").val();
			var tracking_tags_name = cloned.find(".tracking_tags_name").val();
			var tracking_tags_slug = cloned.find(".tracking_tags_slug").val();
			var tracking_tags_pixel = cloned.find(".tracking_tags_pixel").text();

			var template = $(".tracking_tags_template_container").html();

			var container = $("#tracking_tags_container");

			make_last_added_tag_active();

			container.append(template);

			var last = reindex_tracking_tags();

			last
				.find(".tracking_tags_time")
				.val(tracking_tags_time)
				.trigger("change");
			last
				.find(".tracking_tags_name")
				.val(tracking_tags_name)
				.trigger("change");
			last
				.find(".tracking_tags_slug")
				.val(tracking_tags_slug)
				.trigger("change");
			last.find(".tracking_tags_pixel").text(tracking_tags_pixel);
			last.addClass("auto_action_item_active");

			var offset = last.offset();
			var topScroll = offset.top - 40;
			$("html, body").stop().animate({ scrollTop: topScroll }, 500);
		});

		function make_last_added_tag_active() {
			var items = $("#tracking_tags_container .tracking_tag_item");

			if (items.length) {
				items.each(function (index) {
					var item = $(this);

					item.removeClass("auto_action_item_active");
				});
			}
		}

		function reindex_tracking_tags() {
			var containers = $("#tracking_tags_container .tracking_tag_item");

			if (containers.length) {
				var last;
				var last_continer;

				containers.each(function (index) {
					var container = $(this);
					last_continer = container;
					var num = index + 1;
					last = num;

					var header = container.find(".auto_action_header h4");
					var header_num = header.find("span.index_holder");
					header_num.text(num);

					var tracking_tags_time = container.find(".tracking_tags_time");
					var tracking_tags_name = container.find(".tracking_tags_name");
					var tracking_tags_slug = container.find(".tracking_tags_slug");
					var tracking_tags_pixel = container.find(".tracking_tags_pixel");

					if (tracking_tags_time.length) {
						tracking_tags_time.attr("id", "tracking_tags_time_" + index);
						tracking_tags_time.attr(
							"name",
							"tracking_tags[" + index + "][time]"
						);
					}

					if (tracking_tags_name.length) {
						tracking_tags_time.attr("id", "tracking_tags_name_" + index);
						tracking_tags_name.attr(
							"name",
							"tracking_tags[" + index + "][name]"
						);
					}

					if (tracking_tags_slug.length) {
						tracking_tags_time.attr("id", "tracking_tags_slug_" + index);
						tracking_tags_slug.attr(
							"name",
							"tracking_tags[" + index + "][slug]"
						);
					}

					if (tracking_tags_pixel.length) {
						tracking_tags_pixel.attr("id", "tracking_tags_pixel_" + index);
						tracking_tags_pixel.attr(
							"name",
							"tracking_tags[" + index + "][pixel]"
						);
					}
				});

				return last_continer;
			}
		}

		$(document.body).on("click", ".deleteTrackingTag", function () {
			var btn = $(this);
			var container = btn.parents(".additional_auto_action_item");

			container.remove();

			var last = reindex_tracking_tags();

			var offset = last.offset();
			var topScroll = offset.top - 40;
			$("html, body").stop().animate({ scrollTop: topScroll }, 500);
		});

		$(document.body).on(
			"change paste keyup",
			".tracking_tags_time, .tracking_tags_name, .tracking_tags_slug",
			function () {
				var item = $(this).parents(".tracking_tag_item");
				var tracking_tags_time = item.find(".tracking_tags_time").val();
				var tracking_tags_name = item.find(".tracking_tags_name").val();
				var tracking_tags_slug = item.find(".tracking_tags_slug").val();

				var auto_action_desc_holder = item.find(".auto_action_desc_holder");
				var auto_action_desc = "";

				if ("" !== tracking_tags_time.trim()) {
					auto_action_desc = "(";
					auto_action_desc = auto_action_desc + tracking_tags_time.trim();
				}

				if ("" !== tracking_tags_name.trim()) {
					if ("" !== auto_action_desc.trim()) {
						auto_action_desc = auto_action_desc + " - ";
					} else {
						auto_action_desc = "(";
					}

					auto_action_desc = auto_action_desc + tracking_tags_name.trim();
				}

				if ("" !== auto_action_desc.trim()) {
					auto_action_desc = auto_action_desc + ")";
				}

				auto_action_desc_holder.text(auto_action_desc);
			}
		);

		$(document).on('click', '.auto_action_header', function( e ) {
			$(this).parent().find('.optionSelector').each(function() {
				var option = $(this);

				if ( option.hasClass('optionSelectorSelected') ) {
					option.trigger('click');
				}
			})
		});

		$(document.body).on("click", "#createAutoAction", function () {
			make_last_added_active();
			var template = $(".additional_auto_action_template_container").html();
			var container = $(".additional_auto_action_container");
			container.append(template);

			var last = reindex_additional_ctas();
		
			container
			.find("#additional-autoaction__cta_position__")
			.attr("id", "additional-autoaction__cta_position__" + last);


			container.find("#additional-autoaction__cta_position__"+last).attr("name", "additional-autoaction__cta_position__"+ last);

			var position = $("#additional-autoaction__cta_position__"+last).val();

			container.find('.opts-grp-additional-autoaction__cta_position__').each(function() {
				var $this = $(this);
				var newClass = 'opts-grp-additional-autoaction__cta_position__' + last;
				var oldDataId = 'additional-autoaction__cta_position__';
				var newDataId = 'additional-autoaction__cta_position__'+last;

				// Remove the old class and add the new class
				$this.removeClass(function(index, className) {
					return (className.match(/(^|\s)opts-grp-additional-autoaction__cta_position__\S+/g) || []).join(' ');
				}).addClass(newClass);
			
				if ($this.attr('data-id') === oldDataId) {
					$this.attr('data-id', newDataId);
				}

				console.log("Cusrrent position: ",position);
				// Check the data-value and click the current element if it matches
				if ($this.attr("data-value") === position) {
					setTimeout(function() {
						$this.click(); // Use $this, which refers to the current element
					}, 200);
				}
			});

			$(document).on('click', '.optionSelector', function (e) {
				e.preventDefault(); // Prevent default anchor behavior
			
				var clickedAnchor = $(this); // The clicked anchor tag
				var newValue = clickedAnchor.data('value'); // Get the data-value attribute
				var container = clickedAnchor.closest('.inputSection'); // Get the parent container
				var inputField = container.find('input[type="hidden"]'); // Find the hidden input field
			
				// Update the input field's value
				inputField.val(newValue).trigger('change');
			
				// Manage the selected state (optional)
				container.find('.optionSelector').removeClass('optionSelectorSelected'); // Remove 'selected' class from all
				clickedAnchor.addClass('optionSelectorSelected'); // Add 'selected' class to the clicked anchor
			});


			container
			.find("#additional-autoaction__cta_alignment__")
			.attr("id", "#additional-autoaction__cta_alignment__" + last);

			var Alignment = $("#additional-autoaction__cta_alignment__"+last).val();
			
			container.find("#additional-autoaction__cta_alignment__"+last).attr("name", "additional-autoaction__cta_alignment__"+ last);

			container.find('.opts-grp-additional-autoaction__cta_alignment__').each(function() {
				var $this = $(this);
				var newClass = 'opts-grp-additional-autoaction__cta_alignment__' + last;
				var oldDataId = 'additional-autoaction__cta_alignment__';
				var newDataId = 'additional-autoaction__cta_alignment__'+last;

				// Remove the old class and add the new class
				$this.removeClass(function(index, className) {
					return (className.match(/(^|\s)opts-grp-additional-autoaction__cta_alignment__\S+/g) || []).join(' ');
				}).addClass(newClass);
			
				if ($this.attr('data-id') === oldDataId) {
					$this.attr('data-id', newDataId);
				}

				// Check the data-value and click the current element if it matches
				if ($this.attr("data-value") === Alignment) {
					setTimeout(function() {
						$this.click(); // Use $this, which refers to the current element
					}, 200);
				}
			});

			$('#additional-autoaction__cta_position__'+last).on(
				"change",
				function () {
					// Get the current value of the input field
					var ctaPositionValue = $(this).val();
					// Check the value and toggle the display of .cta_alignement_new accordingly
					if (ctaPositionValue === "overlay") {
						$(".cta_alignement_new").css("display", "block"); // Show the element
						$(".dashboard-cta-width-cont").css("display", "block");
						$(".default_cta_alignement_new").css("display", "block"); // Show the element
						$(".default-dashboard-cta-width-cont").css("display", "block");
						$(".default-dashboard-cta-transparency-cont").css("display", "block");
					} else {
						$(".cta_alignement_new").css("display", "none"); // Hide the element
						$(".dashboard-cta-width-cont").css("display", "none");
						$(".default_cta_alignement_new").css("display", "none"); // Hide the element
						$(".default-dashboard-cta-width-cont").css("display", "none");
						$(".default-dashboard-cta-transparency-cont").css("display", "none");
					}
				}
			);
	
			$("#cta_position, #cta_position_additional").on("change", function () {
				// Get the current value of the input field
				var ctaPositionValue = $(this).val();
				if (ctaPositionValue === "overlay") {
					$(".default_cta_alignement_new").css("display", "block"); // Show the element
					$(".default-dashboard-cta-width-cont").css("display", "block");
				} else {
					$(".default_cta_alignement_new").css("display", "none"); // Hide the element
					$(".default-dashboard-cta-width-cont").css("display", "none");
				}
			});


			container.find(
					'.auto_action_item_active input[name^="additional-autoaction__auto_action_time__"]'
				)
				.inputmask({
					mask: "9{1,6}:59",
					definitions: { 5: { validator: "[0-5]" } },
				});
			container
				.find(
					'.auto_action_item_active input[name^="additional-autoaction__auto_action_time_end__"]'
				)
				.inputmask({
					mask: "9{1,6}:59",
					definitions: { 5: { validator: "[0-5]" } },
				});

			var newColorPicker = container.find(
				'.auto_action_item_active input[name^="additional-autoaction__replay_order_color__"]'
			);
			cloneCTAColorPicker(newColorPicker, "");

			scrollToLast(last);


			container.find('.opts-grp-cta_position_additional').each(function() {
				var option = $(this);

				if ( option.hasClass('optionSelectorSelected') ) {
					option.trigger('click');
				}
			});
		});

		$(document.body).on(
			"change paste keyup",
			'input[name^="additional-autoaction__auto_action_time__"], input[name^="additional-autoaction__auto_action_time_end__"], input[name^="additional-autoaction__auto_action_btn_copy__"]',
			function () {
				var container = $(this).parents(".auto_action_item");
				var clonedAuto_action_time = container
					.find('input[name^="additional-autoaction__auto_action_time__"]')
					.val();
				var clonedAuto_action_time_end = container
					.find('input[name^="additional-autoaction__auto_action_time_end__"]')
					.val();
				var clonedAuto_action_btn_copy = container
					.find('input[name^="additional-autoaction__auto_action_btn_copy__"]')
					.val();

				let auto_action_desc = "";

				if ("" !== clonedAuto_action_time) {
					auto_action_desc = auto_action_desc + clonedAuto_action_time;
				}

				if ("" !== clonedAuto_action_time_end) {
					if ("" !== auto_action_desc) {
						auto_action_desc = auto_action_desc + " - ";
					} else {
						auto_action_desc = auto_action_desc + "0:00 - ";
					}

					auto_action_desc = auto_action_desc + clonedAuto_action_time_end;
				}

				if ("" !== clonedAuto_action_btn_copy) {
					if ("" !== auto_action_desc) {
						auto_action_desc = auto_action_desc + " - ";
					}

					auto_action_desc = auto_action_desc + clonedAuto_action_btn_copy;
				}

				if ("" !== auto_action_desc) {
					auto_action_desc = "(" + auto_action_desc + ")";
				}

				container
					.find(".auto_action_header .auto_action_desc_holder")
					.text(auto_action_desc);
			}
		);

		$(document).on("change", "#auto_action", function () {
			let value = $(this).val(); // Get the value of the hidden input
			let ele = $("div.auto_action_header:nth-child(2) > h4 > span.auto_action_desc_holder");
		
			// Check the value and show or hide accordingly
			if (value === 'time') {
				ele.show();
			} else if (value === 'start') {
				ele.hide();
			}
		});

		$(document).on("click", ".cloneAutoAction", function () {
			var clonedContainer = $(this)
				.parents(".auto_action_item_active")
				.removeClass("auto_action_item_active")
				.clone();
			var indexHolder = clonedContainer.find(
				".auto_action_header > h4 > span.index_holder"
			);
			var oldCtaIndex = indexHolder.text();
			var container = $("#additional_auto_action_container");
			var newCtaIndex =
				container.children(".additional_auto_action_item").length + 1;
			indexHolder.text(newCtaIndex);
			container.append(clonedContainer);

			var editor_id_prefix = "additional-autoaction__auto_action_copy__";
			clonedContainer.find("input", "textarea").each(function (i, input) {
				let input_name = $(input).attr("name");
				if (input_name) {
					input_name = input_name.replace(
						"__" + oldCtaIndex,
						"__" + newCtaIndex
					);
					$(input).attr("name", input_name);
				}

				let input_id = $(input).attr("id");
				if (input_id) {
					input_id = input_id.replace("__" + oldCtaIndex, "__" + newCtaIndex);
					$(input).attr("id", input_id);
				}
			});

			clonedContainer
				.find(
					'a[class*="opts-grp-additional-autoaction__cta_position__"], a[class*="opts-grp-additional-autoaction__cta_iframe__"]'
				)
				.each(function () {
					let position_button = $(this);
					let position_button_id = position_button.data("id");

					if (position_button_id) {
						let has_same_class = position_button.hasClass(
							"opts-grp-" + position_button_id
						);
						position_button.removeClass("opts-grp-" + position_button_id);
						let position_button_id_array = position_button_id.split("__");
						position_button_id =
							position_button_id_array[0] +
							"__" +
							position_button_id_array[1] +
							"__" +
							newCtaIndex;
						position_button.attr("data-id", position_button_id);
						if (has_same_class) {
							position_button.addClass("opts-grp-" + position_button_id);
						}
					}
				});
			
			clonedContainer
				.find("#additional-autoaction__cta_alignment__" + oldCtaIndex)
				.attr("id", "#additional-autoaction__cta_alignment__" + newCtaIndex);

			var clonedAlignment = $("#additional-autoaction__cta_alignment__"+newCtaIndex).val();
			
			clonedContainer.find("#additional-autoaction__cta_alignment__"+newCtaIndex).attr("name", "additional-autoaction__cta_alignment__"+ newCtaIndex);

			clonedContainer.find('.opts-grp-additional-autoaction__cta_alignment__'+ oldCtaIndex).each(function() {
				var $this = $(this);
				var newClass = 'opts-grp-additional-autoaction__cta_alignment__' + newCtaIndex;
				var oldDataId = 'additional-autoaction__cta_alignment__'+oldCtaIndex;
				var newDataId = 'additional-autoaction__cta_alignment__'+newCtaIndex;

				// Remove the old class and add the new class
				$this.removeClass(function(index, className) {
					return (className.match(/(^|\s)opts-grp-additional-autoaction__cta_alignment__\S+/g) || []).join(' ');
				}).addClass(newClass);
			
				if ($this.attr('data-id') === oldDataId) {
					$this.attr('data-id', newDataId);
				}

				// Check the data-value and click the current element if it matches
				if ($this.attr("data-value") === clonedAlignment) {
					setTimeout(function() {
						$this.click(); // Use $this, which refers to the current element
					}, 200);
				}
			});

			$('#additional-autoaction__cta_position__'+newCtaIndex).on(
				"change",
				function () {
					// Get the current value of the input field
					var ctaPositionValue = $(this).val();
	
					// Check the value and toggle the display of .cta_alignement_new accordingly
					if (ctaPositionValue === "overlay") {
						$(".cta_alignement_new").css("display", "block"); // Show the element
						$(".dashboard-cta-width-cont").css("display", "block");
						$(".default_cta_alignement_new").css("display", "block"); // Show the element
						$(".default-dashboard-cta-width-cont").css("display", "block");
						$(".default-dashboard-cta-transparency-cont").css("display", "block");
					} else {
						$(".cta_alignement_new").css("display", "none"); // Hide the element
						$(".dashboard-cta-width-cont").css("display", "none");
						$(".default_cta_alignement_new").css("display", "none"); // Hide the element
						$(".default-dashboard-cta-width-cont").css("display", "none");
						$(".default-dashboard-cta-transparency-cont").css("display", "none");
					}
				}
			);
	
			$("#cta_position, #cta_position_additional").on("change", function () {
				// Get the current value of the input field
				var ctaPositionValue = $(this).val();
				if (ctaPositionValue === "overlay") {
					$(".default_cta_alignement_new").css("display", "block"); // Show the element
					$(".default-dashboard-cta-width-cont").css("display", "block");
				} else {
					$(".default_cta_alignement_new").css("display", "none"); // Hide the element
					$(".default-dashboard-cta-width-cont").css("display", "none");
				}
			});

			/* CTA IFRAME FIELD */
			clonedContainer
				.find("#additional-autoaction__cta_iframe__" + oldCtaIndex)
				.attr("id", "#additional-autoaction__cta_iframe__" + newCtaIndex);
			clonedContainer
				.find("#additional-autoaction__cta_iframe__" + oldCtaIndex + "_no")
				.attr("id", "additional-autoaction__cta_iframe__" + newCtaIndex + "_no")
				.attr("class", "additional-autoaction__cta_iframe__" + newCtaIndex);
			clonedContainer
				.find("#additional-autoaction__cta_iframe__" + oldCtaIndex + "_yes")
				.attr(
					"id",
					"additional-autoaction__cta_iframe__" + newCtaIndex + "_yes"
				)
				.attr("class", "additional-autoaction__cta_iframe__" + newCtaIndex);
			clonedContainer
				.find(
					'a[class*="opts-grp-additional-autoaction__cta_iframe__' +
					oldCtaIndex +
					'"]'
				)
				.attr(
					"class",
					"opts-grp-additional-autoaction__cta_iframe__" + newCtaIndex
				);
			clonedContainer
				.find("#additional-autoaction__cta_iframe_sc__" + oldCtaIndex)
				.attr("id", "#additional-autoaction__cta_iframe_sc__" + newCtaIndex);
			/* CTA IFRAME FIELD */

			clonedContainer
				.find(
					"#wp-additional-autoaction__auto_action_copy__" +
					oldCtaIndex +
					"-wrap"
				)
				.parent()
				.html("")
				.append(
					'<textarea id="' +
					editor_id_prefix +
					newCtaIndex +
					'" name="' +
					editor_id_prefix +
					newCtaIndex +
					'" class="wp-editor" style="width:100%"> ' +
					wp.editor.getContent(editor_id_prefix + oldCtaIndex) +
					"</textarea>"
				);

			wp.editor.initialize(editor_id_prefix + newCtaIndex, {
				tinymce: {
					height: 250,
					teeny: false,
					wpautop: false,
					plugins:
						"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
					toolbar1:
						"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
					toolbar2:
						"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
				},
				quicktags: true,
				mediaButtons: true,
			});

			if (typeof window.tinyMCE !== "undefined") {
				tinyMCE.get(editor_id_prefix + newCtaIndex).remove();
				tinyMCE.execCommand(
					"mceAddEditor",
					false,
					editor_id_prefix + newCtaIndex
				);
			}

			clonedContainer
				.find(
					'input[name^="additional-autoaction__auto_action_time__"], input[name^="additional-autoaction__auto_action_time_end__"]'
				)
				.inputmask({
					mask: "9{1,6}:59",
					definitions: { 5: { validator: "[0-5]" } },
				});

			var newColorPicker = clonedContainer.find(
				'input[name^="additional-autoaction__replay_order_color__"]'
			);
			cloneCTAColorPicker(newColorPicker);

			clonedContainer.addClass("auto_action_item_active").trigger("click");
			scrollToLast(newCtaIndex);
		});

		$(document.body).on(
			"change paste keyup",
			"#auto_action_time, #auto_action_time_end, #auto_action_btn_copy",
			function () {
				var container = $(this).parents(".auto_action_item");
				var clonedAuto_action_time = $("input#auto_action_time").val();
				var clonedAuto_action_time_end = $("#auto_action_time_end").val();
				var clonedAuto_action_btn_copy = $("#auto_action_btn_copy").val();

				let auto_action_desc = "";

				if ("" !== clonedAuto_action_time) {
					auto_action_desc = auto_action_desc + clonedAuto_action_time;
				}

				if ("" !== clonedAuto_action_time_end) {
					if ("" !== auto_action_desc) {
						auto_action_desc = auto_action_desc + " - ";
					} else {
						auto_action_desc = auto_action_desc + "0:00 - ";
					}

					auto_action_desc = auto_action_desc + clonedAuto_action_time_end;
				}

				if ("" !== clonedAuto_action_btn_copy) {
					if ("" !== auto_action_desc) {
						auto_action_desc = auto_action_desc + " - ";
					}

					auto_action_desc = auto_action_desc + clonedAuto_action_btn_copy;
				}

				if ("" !== auto_action_desc) {
					auto_action_desc = "(" + auto_action_desc + ")";
				}

				container
					.find(".auto_action_header .auto_action_desc_holder")
					.text(auto_action_desc);
			}
		);

		$(document.body).on("click", "#cloneMainAutoAction", function () {
			var clonedAuto_action_time = $("input#auto_action_time").val();
			var clonedAuto_action_time_end = $("#auto_action_time_end").val();
			var clonedAuto_action_btn_copy = $("#auto_action_btn_copy").val();
			var clonedAuto_action_url = $("#auto_action_url").val();
			var clonedReplay_order_color = $("#replay_order_color").val();
			var clonedPosition = $("#cta_position").val();
			var clonedAlignment = $("#cta_alignment").val();
			var clonedIframe = $("#cta_iframe").val();
			var clonedIframeSC = $("#cta_iframe_sc").val();
			var clonedAuto_action_copy = tmce_getContent("auto_action_copy");
			var clonedWidth = $("#auto_action_max_width").val();
			var clonedTransparency = $("#auto_action_transparency").val();

			$(this)
				.parents(".default_auto_action_container")
				.removeClass("auto_action_item_active");

			var template = $(
				".additional_auto_action_template_container > .additional_auto_action_item"
			).clone();
			var container = $(".additional_auto_action_container");

			container.append(template);

			var last = reindex_additional_ctas();
			template.find(".auto_action_header > h4 > span.index_holder").text(last);
			template.find('a[data-value="' + clonedPosition + '"]').trigger("click");

			/* CTA IFRAME FIELD */
			template
				.find("#additional-autoaction__cta_iframe__" + last)
				.val(clonedIframe);
			template
				.find("#additional-autoaction__cta_iframe_sc__" + last)
				.val(clonedIframeSC);
			template
				.find("#additional-autoaction__cta_iframe___no")
				.attr("id", "additional-autoaction__cta_iframe__" + last + "_no")
				.attr("class", "additional-autoaction__cta_iframe__" + last);
			template
				.find("#additional-autoaction__cta_iframe___yes")
				.attr("id", "additional-autoaction__cta_iframe__" + last + "_yes")
				.attr("class", "additional-autoaction__cta_iframe__" + last);
			template
				.find(
					'a[class*="opts-grp-additional-autoaction__cta_iframe__' +
					last +
					'"][data-value="' +
					clonedIframe +
					'"]'
				)
				.trigger("click");
			/* CTA IFRAME FIELD */

			template.find("#additional-autoaction__cta_position__").attr("id", "additional-autoaction__cta_position__"+ last);
			template.find("#additional-autoaction__cta_position__"+last).attr("name", "additional-autoaction__cta_position__"+ last);
			
			$('#additional-autoaction__cta_position__'+last).on(
				"change",
				function () {
					// Get the current value of the input field
					var ctaPositionValue = $(this).val();
	
					// Check the value and toggle the display of .cta_alignement_new accordingly
					if (ctaPositionValue === "overlay") {
						$(".cta_alignement_new").css("display", "block"); // Show the element
						$(".dashboard-cta-width-cont").css("display", "block");
						$(".default_cta_alignement_new").css("display", "block"); // Show the element
						$(".default-dashboard-cta-width-cont").css("display", "block");
						$(".default-dashboard-cta-transparency-cont").css("display", "block");
					} else {
						$(".cta_alignement_new").css("display", "none"); // Hide the element
						$(".dashboard-cta-width-cont").css("display", "none");
						$(".default_cta_alignement_new").css("display", "none"); // Hide the element
						$(".default-dashboard-cta-width-cont").css("display", "none");
						$(".default-dashboard-cta-transparency-cont").css("display", "none");
					}
				}
			);


			$('.opts-grp-additional-autoaction__cta_position__').each(function() {
				var $this = $(this);
				var newClass = 'opts-grp-additional-autoaction__cta_position__' + last;

				// Remove the old class and add the new class
				$this.removeClass(function(index, className) {
					return (className.match(/(^|\s)opts-grp-additional-autoaction__cta_position__\S+/g) || []).join(' ');
				}).addClass(newClass);
			
				// Check the data-value and click the current element if it matches
				if ($this.attr("data-value") === clonedPosition) {
					setTimeout(function() {
						$this.click(); // Use $this, which refers to the current element
					}, 200);
				}
			});

			$(".opts-grp-additional-autoaction__cta_position__"+last).attr("data-id", "additional-autoaction__cta_position__"+last);

			template.find("#additional-autoaction__cta_alignment__").attr("id", "additional-autoaction__cta_alignment__"+ last);
			template.find("#additional-autoaction__cta_alignment__"+last).attr("name", "additional-autoaction__cta_alignment__"+ last);
			$('.opts-grp-additional-autoaction__cta_alignment__').each(function() {
				var $this = $(this);
				var newClass = 'opts-grp-additional-autoaction__cta_alignment__' + last;

				// Remove the old class and add the new class
				$this.removeClass(function(index, className) {
					return (className.match(/(^|\s)opts-grp-additional-autoaction__cta_alignment__\S+/g) || []).join(' ');
				}).addClass(newClass);
			
				// Check the data-value and click the current element if it matches
				if ($this.attr("data-value") === clonedAlignment) {
					setTimeout(function() {
						$this.click(); // Use $this, which refers to the current element
					}, 200);
				}
			});

			$(".opts-grp-additional-autoaction__cta_alignment__"+last).attr("data-id", "additional-autoaction__cta_alignment__"+last);
			template
				.find("#additional-autoaction__cta_alignment__" + last)
				.val(clonedAlignment);
			
			template
				.find("#additional-autoaction__auto_action_time__" + last)
				.val(clonedAuto_action_time)
				.inputmask({
					mask: "9{1,6}:59",
					definitions: { 5: { validator: "[0-5]" } },
				});
			template
				.find("#additional-autoaction__auto_action_time_end__" + last)
				.val(clonedAuto_action_time_end)
				.inputmask({
					mask: "9{1,6}:59",
					definitions: { 5: { validator: "[0-5]" } },
				});
			template
				.find("#additional-autoaction__auto_action_btn_copy__" + last)
				.val(clonedAuto_action_btn_copy);
			template
				.find("#additional-autoaction__auto_action_url__" + last)
				.val(clonedAuto_action_url);

			template
				.find("#additional-autoaction__auto_action_max_width__" + last)
				.val(clonedWidth);
			template
				.find("#additional-autoaction__auto_action_transparency__" + last)
				.val(clonedTransparency);

			let auto_action_desc = "";

			if ("" !== clonedAuto_action_time) {
				auto_action_desc = auto_action_desc + clonedAuto_action_time;
			}

			if ("" !== clonedAuto_action_time_end) {
				if ("" !== auto_action_desc) {
					auto_action_desc = auto_action_desc + " - ";
				} else {
					auto_action_desc = auto_action_desc + "0:00 - ";
				}

				auto_action_desc = auto_action_desc + clonedAuto_action_time_end;
			}

			if ("" !== clonedAuto_action_btn_copy) {
				if ("" !== auto_action_desc) {
					auto_action_desc = auto_action_desc + " - ";
				}

				auto_action_desc = auto_action_desc + clonedAuto_action_btn_copy;
			}

			if ("" !== auto_action_desc) {
				auto_action_desc = "(" + auto_action_desc + ")";
			}

			template
				.find(".auto_action_header .auto_action_desc_holder")
				.text(auto_action_desc);

			var newColorPicker = template.find(
				"#additional-autoaction__replay_order_color__" + last
			);
			cloneCTAColorPicker(newColorPicker, clonedReplay_order_color);

			var last_editorId = "additional-autoaction__auto_action_copy__" + last;

			wp.editor.remove(last_editorId);
			$("#" + last_editorId).val(clonedAuto_action_copy);
			wp.editor.initialize(last_editorId, {
				tinymce: {
					height: 250,
					teeny: false,
					wpautop: false,
					plugins:
						"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
					toolbar1:
						"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
					toolbar2:
						"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
				},
				quicktags: true,
				mediaButtons: true,
			});

			scrollToLast(last);
		});

		/**
		 * Clone webinar CTA buttons color picker
		 * @param colorPicker (required) color picker dom element to clone
		 * @param colorValue (optional) color value in HEX string
		 */
		function cloneCTAColorPicker(colorPicker, colorValue) {
			if (colorValue) {
				colorPicker.val(colorValue);
			}
			colorPicker
				.parents(".inputSection > .wp-picker-container")
				.replaceWith(colorPicker);
			jQuery(colorPicker).wpColorPicker({
				clear: function () {
					jQuery(this).prev().find(".cp-picker").val("transparent");
				},
			});
		}

		function scrollToLast(last) {
			var p = $(
				"#additional_auto_action_container .additional_auto_action_item:last"
			);
			var offset = p.offset();
			var topScroll = offset.top - 40;
			$("html, body").stop().animate({ scrollTop: topScroll }, 500);
		}

		function tmce_getContent(editor_id, textarea_id) {
			if (typeof editor_id == "undefined") editor_id = wpActiveEditor;
			if (typeof textarea_id == "undefined") textarea_id = editor_id;

			if (
				jQuery("#wp-" + editor_id + "-wrap").hasClass("tmce-active") &&
				tinyMCE.get(editor_id)
			) {
				return tinyMCE.get(editor_id).getContent();
			} else {
				return jQuery("#" + textarea_id).val();
			}
		}

		function tmce_setContent(content, editor_id, textarea_id) {
			if (typeof editor_id == "undefined") editor_id = wpActiveEditor;
			if (typeof textarea_id == "undefined") textarea_id = editor_id;

			if (
				jQuery("#wp-" + editor_id + "-wrap").hasClass("tmce-active") &&
				tinyMCE.get(editor_id)
			) {
				tinyMCE.get(editor_id).setContent(content);
			} else {
				jQuery("#" + textarea_id).val(content);
			}
		}

		$(document.body).on("change", "#webinar_template", function () {
			var input = $(this);
			var template = input.val();
			var visible_for_classic = $(".section-visible-for-webinar-classic");
			var visible_for_modern = $(".section-visible-for-webinar-modern");

			if ("classic" === template) {
				if (visible_for_classic.length) {
					visible_for_classic.each(function () {
						$(this).show();
					});
				}

				if (visible_for_modern.length) {
					visible_for_modern.each(function () {
						$(this).hide();
					});
				}
			} else {
				if (visible_for_classic.length) {
					visible_for_classic.each(function () {
						$(this).hide();
					});
				}

				if (visible_for_modern.length) {
					visible_for_modern.each(function () {
						$(this).show();
					});
				}
			}
		});

		$(document.body).on("click", ".deleteAutoAction", function (e) {
			e.preventDefault();

			var additional_cta_deleted_item = $(this).parents(
				".additional_auto_action_item"
			);
			var additional_cta_deleted_item_index =
				additional_cta_deleted_item.index();

			additional_cta_deleted_item.remove();

			reindex_additional_ctas_after_delete(additional_cta_deleted_item_index);
		});

		$(document.body).on("click", ".auto_action_header", function () {
			var header = $(this);
			var parent_container = header.parents(".auto_action_item");
			var is_active = parent_container.hasClass("auto_action_item_active");
			var parent_section = header.parents(".we_edit_area");

			var containers = parent_section.find(".auto_action_item");

			if (containers.length) {
				containers.each(function () {
					var container = $(this);
					container.removeClass("auto_action_item_active");
				});
			}

			var indexHolder = header.find("h4 > span.index_holder");
			var currentCtaIndex = indexHolder.text().trim();
			
			$('#additional-autoaction__cta_position__'+currentCtaIndex).on(
				"change",
				function () {
					// Get the current value of the input field
					var ctaPositionValue = $(this).val();
	
					// Check the value and toggle the display of .cta_alignement_new accordingly
					if (ctaPositionValue === "overlay") {
						$(".cta_alignement_new").css("display", "block"); // Show the element
						$(".dashboard-cta-width-cont").css("display", "block");
						$(".default_cta_alignement_new").css("display", "block"); // Show the element
						$(".default-dashboard-cta-width-cont").css("display", "block");
						$(".default-dashboard-cta-transparency-cont").css("display", "block");
					} else {
						$(".cta_alignement_new").css("display", "none"); // Hide the element
						$(".dashboard-cta-width-cont").css("display", "none");
						$(".default_cta_alignement_new").css("display", "none"); // Hide the element
						$(".default-dashboard-cta-width-cont").css("display", "none");
						$(".default-dashboard-cta-transparency-cont").css("display", "none");
					}
				}
			);
	
			$("#cta_position, #cta_position_additional").on("change", function () {
				// Get the current value of the input field
				var ctaPositionValue = $(this).val();
				if (ctaPositionValue === "overlay") {
					$(".default_cta_alignement_new").css("display", "block"); // Show the element
					$(".default-dashboard-cta-width-cont").css("display", "block");
				} else {
					$(".default_cta_alignement_new").css("display", "none"); // Hide the element
					$(".default-dashboard-cta-width-cont").css("display", "none");
				}
			});

			if (!is_active) {
				parent_container.addClass("auto_action_item_active");

				var offset = header.offset();
				var topScroll = offset.top - 40;
				$("html, body").stop().animate({ scrollTop: topScroll }, 500);
			}
		});

		function make_last_added_active() {
			var containers = $("#we_edit_auto_actions .auto_action_item");

			if (containers.length && containers.length > 1) {
				containers.each(function (index) {
					var container = $(this);
					container.removeClass("auto_action_item_active");
				});
			}
		}

		function reindex_additional_ctas_after_delete(
			additional_cta_deleted_item_index
		) {
			var editor_id_prefix = "additional-autoaction__auto_action_copy__";
			var additional_cta_items = $(
				"#additional_auto_action_container .additional_auto_action_item"
			);

			// wp.editor.remove(editor_id_prefix + additional_cta_deleted_item_index);

			if (additional_cta_items.length > 0) {
				additional_cta_items.each(function (
					additional_cta_index,
					additional_cta_item
				) {
					if (additional_cta_index < additional_cta_deleted_item_index) return; //only process items after deleted index to reduce processing time and browser load

					var additional_cta_item = $(additional_cta_item);
					var indexHolder = $(additional_cta_item).find(
						".auto_action_header > h4 > span.index_holder"
					);
					var oldCtaIndex = indexHolder.text();
					var newCtaIndex = additional_cta_index + 1;

					indexHolder.text(newCtaIndex);

					additional_cta_item
						.find("input", "textarea")
						.each(function (i, input) {
							let input_name = $(input).attr("name");
							if (input_name) {
								input_name = input_name.replace(
									"__" + oldCtaIndex,
									"__" + newCtaIndex
								);
								$(input).attr("name", input_name);
							}

							let input_id = $(input).attr("id");
							if (input_id) {
								input_id = input_id.replace(
									"__" + oldCtaIndex,
									"__" + newCtaIndex
								);
								$(input).attr("id", input_id);
							}
						});

					additional_cta_item
						.find(
							'a[class*="opts-grp-additional-autoaction__cta_position__"], a[class*="opts-grp-additional-autoaction__cta_iframe__"]'
						)
						.each(function () {
							let position_button = $(this);
							let position_button_id = position_button.data("id");

							if (position_button_id) {
								let has_same_class = position_button.hasClass(
									"opts-grp-" + position_button_id
								);
								position_button.removeClass("opts-grp-" + position_button_id);
								let position_button_id_array = position_button_id.split("__");
								position_button_id =
									position_button_id_array[0] +
									"__" +
									position_button_id_array[1] +
									"__" +
									newCtaIndex;
								position_button.attr("data-id", position_button_id);
								if (has_same_class) {
									position_button.addClass("opts-grp-" + position_button_id);
								}
							}
						});

					wp.editor.remove(editor_id_prefix + oldCtaIndex);

					additional_cta_item.find("#" + editor_id_prefix + oldCtaIndex).attr({
						id: editor_id_prefix + newCtaIndex,
						name: editor_id_prefix + newCtaIndex,
						class: "wp-editor",
						style: "width:100%",
					});

					wp.editor.initialize(editor_id_prefix + newCtaIndex, {
						tinymce: {
							height: 250,
							teeny: false,
							wpautop: false,
							plugins:
								"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
							toolbar1:
								"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
							toolbar2:
								"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
						},
						quicktags: true,
						mediaButtons: true,
					});

					if (typeof window.tinyMCE !== "undefined") {
						tinyMCE.get(editor_id_prefix + newCtaIndex).remove();
						tinyMCE.execCommand(
							"mceAddEditor",
							false,
							editor_id_prefix + newCtaIndex
						);
					}

					additional_cta_item
						.find(
							'input[name^="additional-autoaction__auto_action_time__"], input[name^="additional-autoaction__auto_action_time_end__"]'
						)
						.inputmask("remove");
					additional_cta_item
						.find(
							'input[name^="additional-autoaction__auto_action_time__"], input[name^="additional-autoaction__auto_action_time_end__"]'
						)
						.inputmask({
							mask: "9{1,6}:59",
							definitions: { 5: { validator: "[0-5]" } },
						});

					var newColorPicker = additional_cta_item.find(
						'input[name^="additional-autoaction__replay_order_color__"]'
					);
					cloneCTAColorPicker(newColorPicker);
				});
			}
		}

		function reindex_additional_ctas(cb) {
			var containers = $(
				"#additional_auto_action_container .additional_auto_action_item"
			);

			if (containers.length) {
				var last;

				containers.each(function (index) {
					var container = $(this);
					var num = index + 1;
					last = num;
					var header = container.find(".auto_action_header h4");
					var header_num = header.find("span.index_holder");
					header_num.text(num);

					var inputs = container.find(
						".inputField.elem, #additional-autoaction__cta_position, #additional-autoaction__cta_iframe"
					);

					if (inputs.length) {
						inputs.each(function () {
							var input = $(this);
							var id = input.attr("id");
							var id_array = id.split("__");
							id = id_array[0] + "__" + id_array[1] + "__" + num;
							input.attr("id", id);
							input.attr("name", id);
						});
					}

					container
						.find(
							"a.opts-grp-additional-autoaction__cta_position, a.opts-grp-additional-autoaction__cta_iframe"
						)
						.each(function () {
							let position_button = $(this);
							let position_button_id = position_button.data("id");

							if (position_button_id) {
								let has_same_class = position_button.hasClass(
									"opts-grp-" + position_button_id
								);
								position_button.removeClass("opts-grp-" + position_button_id);
								let position_button_id_array = position_button_id.split("__");
								position_button_id =
									position_button_id_array[0] +
									"__" +
									position_button_id_array[1] +
									"__" +
									num;
								position_button.attr("data-id", position_button_id);
								if (has_same_class) {
									position_button.addClass("opts-grp-" + position_button_id);
								}
							}
						});

					var editorId = "additional-autoaction__auto_action_copy__" + num;

					var textareas = container.find(".inputTextarea.elem");

					if (textareas.length) {
						textareas.each(function () {
							var input = $(this);
							var id = input.attr("id");
							var id_array = id.split("__");
							id = id_array[0] + "__" + id_array[1] + "__" + num;
							input.attr("id", id);
							input.attr("name", id);
						});
					}
				
					wp.editor.remove(editorId);
					wp.editor.initialize(editorId, {
						tinymce: {
							height: 250,
							teeny: false,
							wpautop: false,
							plugins:
								"charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor WordPress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview",
							toolbar1:
								"formatselect bold italic | bullist numlist | blockquote wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv",
							toolbar2:
								"alignjustify forecolor underline strikethrough hr | pastetext removeformat charmap | outdent indent | undo redo | wp_help",
						},
						quicktags: true,
						mediaButtons: true,
					});
				});

				return last;
			}
		}

		function resetPickadateSelection(phpDateFormat) {
			if ($(".createWrapper").length) {
				var $input = $(".dp-date").pickadate(),
					picker = $input.pickadate("picker"),
					selectedDate = picker.get("select"),
					selectedDateObject = new Date(selectedDate.obj.getTime());

				$.post(
					ajaxurl,
					{
						action: "webinarignition_ajax_convert_php_to_js_date_format",
						security: wp_nonce,
						date_format: phpDateFormat,
					},
					function (response) {
						picker.component.settings.format = response.data.date_format;
						picker.set("select", selectedDateObject);
					}
				);
			}
		}

		function resetPickatimeSelection(time_format) {
			var $input = $(".timepicker").pickatime(),
				picker = $input.pickatime("picker");
			selectedTime = picker.get("select");

			$.post(
				ajaxurl,
				{
					action: "webinarignition_ajax_convert_wp_to_js_time_format",
					security: wp_nonce,
					time_format: time_format,
				},
				function (response) {
					picker.component.settings.format = response.data.time_format;
					picker.set("select", [selectedTime.hour, selectedTime.mins]);
				}
			);
		}

		function updateTimeFormat() {
			// Get the selected time format input
			var selectedFormat = $('input[name="time_format"]:checked');
			if (selectedFormat.length) {
				var newVal = selectedFormat.val();
				var examplePreview = selectedFormat.siblings(".format-i18n").text();
				$('input[name="time_format_custom"]').val(newVal);
				$("#time_format_preview").text(examplePreview);
	
				if ($(".createWrapper").length) {
					resetPickatimeSelection(newVal);
				}
			}
		}

		$('#time_format_custom').on("change keyup", function () {
			var customFormat = $('#time_format_custom').val().trim(); // Get and trim the custom format
			var selectedTime = $('#time_format_preview');             // Get the preview element
		
			// Get the current time
			var now = new Date();
		
			// Define a function to format the time based on the custom format
			function formatTime(date, format) {
				let hours = date.getHours();
				let minutes = date.getMinutes();
				let seconds = date.getSeconds();
		
				// Convert to 12-hour format for custom formats requiring it
				let period = hours >= 12 ? 'PM' : 'AM';
				let twelveHour = hours % 12 || 12;
		
				// Replace placeholders in the custom format
				return format
					.replace(/HH/g, hours < 10 ? '0' + hours : hours)   // 24-hour format with leading zero
					.replace(/H/g, hours)                              // 24-hour format without leading zero
					.replace(/hh/g, twelveHour < 10 ? '0' + twelveHour : twelveHour) // 12-hour format with leading zero
					.replace(/h/g, twelveHour)                         // 12-hour format without leading zero
					.replace(/g/g, twelveHour)                         // 12-hour format without leading zero (same as 'h')
					.replace(/mm/g, minutes < 10 ? '0' + minutes : minutes) // Minutes with leading zero
					.replace(/m/g, minutes)                            // Minutes without leading zero
					.replace(/i/g, minutes < 10 ? '0' + minutes : minutes) // Minutes with leading zero (same as 'mm')
					.replace(/ss/g, seconds < 10 ? '0' + seconds : seconds) // Seconds with leading zero
					.replace(/s/g, seconds)                            // Seconds without leading zero
					.replace(/AA/g, period.toUpperCase())              // AM/PM uppercase
					.replace(/A/g, period.toUpperCase())               // AM/PM uppercase
					.replace(/aa/g, period.toLowerCase())              // am/pm lowercase
					.replace(/a/g, period.toLowerCase());              // am/pm lowercase
			}
		
			try {
				// Clean up the format string if needed
				let cleanedFormat = customFormat.replace(/\s{2,}/g, ' '); // Remove excessive spaces
				selectedTime.text(formatTime(now, cleanedFormat));        // Update preview
			} catch (error) {
				selectedTime.text('Invalid format'); // Handle invalid format gracefully
			}
		});
		
		
	
		// Run the function on page load
		updateTimeFormat();
	
		// Bind the function to the click event
		$('input[name="time_format"]').on("click", function () {
			if ("time_format_custom_radio" !== $(this).attr("id")) {
				updateTimeFormat();
			}
		});

		$('input[name="time_format_custom"]').on("click input", function () {
			$("#time_format_custom_radio").prop("checked", true).val($(this).val());
		});

		$('input[name="date_format_custom_new"]').on("change", function () {
			if ($(this).val() !== "custom") {
				$('input[name="date_format_custom"]').val($(this).val());
				$('#date_format_custom_radio').val($(this).val());

			}

			$('#wi_day_string_input input[type="radio"]').trigger("change");
		});

		//Input Day
		$('#wi_day_string_input input[type="radio"]').change(function (e) {
			$("#wi_day_string").text($(this).data("string"));
			$('input[name="wi_show_day"]').trigger("change");
		});

		$('input[name="wi_show_day"]').change(function (e) {
			var date_format = $('input[name="date_format_custom_new"]:checked').val();
			var day_string = $('#wi_day_string_input input[type="radio"]:checked').val();
			var custom_string = $('input[name="date_format_custom"]').val();
		
			// If custom format is selected, keep the existing format
			if (date_format === "custom") {
				date_format = custom_string; 
			}
			// Check if the "Show Day" checkbox is checked
			if (this.checked) {
				// Ensure there's no duplicate day format in `custom_string`
				custom_string = custom_string.replace(/\b(D|l)\b/g, '').trim(); 
				$('input[name="date_format_custom"]').val(day_string + " " + custom_string);
				$('#date_format_custom_radio').val(day_string + " " + custom_string);
			} else {
				// Remove any day format if "Show Day" is unchecked
				custom_string = custom_string.replace(/\b(D|l)\b/g, '').trim();
				$('input[name="date_format_custom"]').val(custom_string);
				$('#date_format_custom_radio').val(custom_string);
			}
		
			// Update the date format preview
			var custom_value = $('input[name="date_format_custom"]').val();
			var timezone = $("#apptz").val();

			// Check if the timezone is a numeric offset
			if (/^[+-]?\d{1,2}$/.test(timezone)) {
				// Convert to valid offset format like "+11:00"
				timezone = moment().utcOffset(parseInt(timezone) * 60).format('Z');
			}

			// Convert "+11:00" or "-05:00" into UTC
			if (/^[+-]\d{2}:\d{2}$/.test(timezone)) {
				var utcTime = moment().utcOffset(timezone).format('YYYY-MM-DD HH:mm:ss');
				console.log(`UTC Time: ${utcTime}`);
			}
			$("#date_format_preview").text(
				moment()
					.tz('UTC')
					.locale($("#applang").val())
					.format(phpToMoment(custom_value))
			);
		});

		$('input[name="date_format_custom"], input[name="time_format_custom"]').on(
			"input",
			function () {
				var format = $(this),
					fieldset = format.closest(".locale_formats"),
					preview = fieldset.find(".formatPreview"),
					spinner = fieldset.find(".spinner"),
					locale = $("#applang").val();

				clearTimeout($.data(this, "timer"));
				$(this).data(
					"timer",
					setTimeout(function () {
						var formatVal = format.val();

						if (formatVal && $(".createWrapper").length) {
							resetPickadateSelection(formatVal);
						}

						if (formatVal) {
							spinner.addClass("is-active");
							$.post(
								ajaxurl,
								{
									action: "webinarignition_ajax_get_date_format",
									security: wp_nonce,
									format: formatVal,
									locale: locale,
								},
								function (d) {
									preview.text(d);
									spinner.removeClass("is-active");
								}
							);
						}
					}, 500)
				);
			}
		);

		if ( $( '.createWrapper #applang' ).length ) {
			$(".createWrapper #applang").on("change", doUpdateDateLocale);
			var date = $('#date_format_custom').val();
		}
		$('#date_format_custom').on('change', function () {
			
			$('#date_format_custom_radio').val($(this).val());
			
			// Update the date format preview
			var custom_value = $(this).val();
			console.log('custom value'+custom_value)
			$("#date_format_preview").text(
				moment()
					.tz($("#apptz").val())
					.locale($("#applang").val())
					.format(phpToMoment(custom_value))
			);
			// $('#wi_day_string_input input[type="radio"]').trigger("change");

		});

		function phpToMoment(str) {
			let replacements = {
				d: "DD",
				D: "ddd",
				j: "D",
				l: "dddd",
				N: "E",
				S: "o",
				w: "e",
				z: "DDD",
				W: "W",
				F: "MMMM",
				m: "MM",
				M: "MMM",
				n: "M",
				t: "", // no equivalent
				L: "", // no equivalent
				o: "YYYY",
				Y: "YYYY",
				y: "YY",
				a: "a",
				A: "A",
				B: "", // no equivalent
				g: "h",
				G: "H",
				h: "hh",
				H: "HH",
				i: "mm",
				s: "ss",
				u: "SSS",
				e: "zz", // deprecated since Moment.js 1.6.0
				I: "", // no equivalent
				O: "", // no equivalent
				P: "", // no equivalent
				T: "", // no equivalent
				Z: "", // no equivalent
				c: "", // no equivalent
				r: "", // no equivalent
				U: "X",
			};

			return str
				.split("")
				.map((chr) => (chr in replacements ? replacements[chr] : chr))
				.join("");
		}

		function doUpdateDateLocale(e) {
			$("#wi_new_webinar_lang_select").addClass("is-active");
			var select_element = $(this);
			var locale = this.value;

			var default_date_radio_label = $("#default_date_radio_label"),
				$input = $(".dp-date").pickadate(),
				date_picker = $input.pickadate("picker"),
				selectedDate = date_picker.get("select"),
				selectedDateDateObject = new Date(selectedDate.obj.getTime());

			var default_time_radio_label = $("#default_time_radio_label"),
				$timeInput = $(".timepicker").pickatime(),
				time_picker = $timeInput.pickatime("picker");
			selectedTime = time_picker.get();

			$.post(
				ajaxurl,
				{
					action: "webinarignition_ajax_get_date_in_chosen_language",
					security: wp_nonce,
					locale: locale,
				},
				function (response) {
					if (response.data === "downloaded") {
						select_element.trigger("change");
						return;
					}

					date_picker.component.settings.format = response.data.js_date_format;
					date_picker.component.settings.monthsFull = response.data.monthsFull;
					date_picker.component.settings.weekdaysFull =
						response.data.weekdaysFull;
					date_picker.component.settings.weekdaysShort =
						response.data.weekdaysShort;

					var day_string = $(
						'#wi_day_string_input input[type="radio"]:checked'
					).val();

					$("#wi_day_string").text(
						response.data["date_in_chosen_day_" + day_string + "_locale"]
					);
					$('#wi_day_string_input input[name="day_string"]').each(function (
						index,
						input
					) {
						$(input).data(
							"string",
							response.data["date_in_chosen_day_" + $(input).val() + "_locale"]
						);
					});

					default_date_radio_label
						.find(".date-time-text")
						.text(response.data.date_in_chosen_locale);
					// $('span#date_format_preview').text( response.data['date_in_chosen_day_' + day_string + '_locale'] + ' ' + response.data.date_in_chosen_locale );
					$("strong.preview_text").text(response.data.preview_text);
					$("span.date-time-text.date-time-custom-text").text(
						response.data.custom_text
					);
					default_date_radio_label
						.find("code")
						.text(response.data.php_date_format);
					default_date_radio_label
						.find('input[name="date_format"]')
						.val(response.data.php_date_format)
						.prop("checked", true);
					$('input[name="date_format_custom"]').val(
						response.data.php_date_format
					);
					// $('input[name="date_format_custom_new"]').val(
					// 	response.data.php_date_format
					// );
					date_picker.set("select", selectedDateDateObject);
					$('input[name="wi_show_day"]').trigger("change");

					$('input[name="time_format"]').each(function() {
						if ($(this).val() === response.data.php_time_format) {
							$(this).prop("checked", true);
							$(this).parent().parent().prependTo('.wi-create-time-format');
						}
					});
					$("span#time_format_preview").text(
						response.data.time_in_chosen_locale
					);
					time_picker.component.settings.format = response.data.js_time_format;
					$('input[name="time_format_custom"]').val(
						response.data.php_time_format
					);
					time_picker.set("select", [selectedTime.hour, selectedTime.mins]);
					if (locale === "en_US") {
						$("#settings_language")
							.find("option:nth-child(2)")
							.prop("selected", true);
					} else {
						$("#settings_language")
							.find("option:nth-child(1)")
							.prop("selected", true);
					}
					$("#wi_new_webinar_lang_select").removeClass("is-active");
				}
			);
		}

		$(".createWrapper #cloneapp").on("change", function () {
			var webinarType = this.value;

			if (webinarType === "auto") {
				// $( 'input[name="date_format_custom"]' ).val( 'D    j. F Y' ).trigger("input");
				$("#wi_show_day_wrap").show();
				$('input[name="wi_show_day"]').prop("checked", true).trigger("change");
			} else {
			var custom_value = $('input[name="date_format_custom"]').val();

			$("#webinar_date").val(
				moment()
					.tz('UTC')
					.locale($("#applang").val())
					.format(phpToMoment(custom_value))
			);

				// $("#wi_show_day_wrap").hide();
				// $('input[name="wi_show_day"]').prop("checked", false).trigger("change");
			}
		});
		$('input[name="wi_show_day"]').on('click', function() {
			// Get the value of the custom date format input field
			let dateFormat = $('#date_format_custom').val();
	
			// Check if the radio is checked
			if ($(this).is(':checked')) {
				// If there's no 'D' at the beginning of the string, add it
				if (!dateFormat.startsWith('D')) {
					$('#date_format_custom').val('D ' + dateFormat);
				}
			} else {
				// If the radio is unchecked, remove 'D' if it's at the beginning
				if (dateFormat.startsWith('D ')) {
					$('#date_format_custom').val(dateFormat.slice(2));
				}
			}
		});

		const url = window.location.href;

		// Split the URL based on the '&' symbol
		const parts = url.split('&');


		if (parts.length > 1) {
			if(parts[1] == 'webinars'){
				$('.webinarIgnition_dashboard_webinar').css({
					'font-weight': 'bold',
					'color': 'white' 
				})
				$('.webinarIgnition_dashboard_main').closest('li').removeClass('current');
			}
			else if(parts[1] == 'create'){
				$('.webinarIgnition_dashboard_create').css({
					'font-weight': 'bold',
					'color': 'white' 
				})
				$('.webinarIgnition_dashboard_main').closest('li').removeClass('current');
			}
			else{
				$('.webinarIgnition_dashboard_webinar').css({
					'font-weight': 'normal',
					'color': 'rgba(240,246,252,.7)' 
				})
				$('.webinarIgnition_dashboard_create').css({
					'font-weight': 'normal',
					'color': 'rgba(240,246,252,.7)' 
				})
			}
		}
		//apply language settings on load
		$("#applang").trigger("change");

		//**** @CodeBasketClosed ****//
	});
	
})(jQuery);
