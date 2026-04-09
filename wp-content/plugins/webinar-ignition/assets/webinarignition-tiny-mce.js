(function() {
    tinymce.PluginManager.add('custom_dropdown', function(editor) {

        var dynamicValues = [];
        var setting = editor.settings.custom_dropdown_items;
        if (setting) {
            // Case: string (JSON)
            if (typeof setting === 'string') {
                try {
                    dynamicValues = JSON.parse(setting);
                } catch (e) {
                    console.warn('custom_dropdown_items: failed to JSON.parse', e);
                }
            }
            // Case: already an array
            else if (Array.isArray(setting)) {
                dynamicValues = setting;
            }
            // Case: object (numeric keys) -> convert to array
            else if (typeof setting === 'object') {
                dynamicValues = Object.keys(setting).map(function(k) {
                    return setting[k];
                });
            }
        }

        // Fallback (only used if nothing valid found)
        if (!dynamicValues || dynamicValues.length === 0) {
            dynamicValues = [
                { text: 'Email Adress', value: '{EMAIL}' },
                { text: 'Participation Reason', value: '{REASON}' },
                { text: 'Title/Salutation', value: '{SALUTATION}' },
                { text: 'Webinar Link', value: '{LINK}' },
                { text: 'Date', value: '{DATE}' },
                { text: 'Title', value: '{TITLE}' },
                { text: 'Host', value: '{HOST}' },
                { text: 'Full Name', value: '{FULLNAME}' },
                { text: 'First Name', value: '{FIRSTNAME}' },
                { text: 'Last Name', value: '{LASTNAME}' },
                { text: 'Phone No', value: '{PHONENUM}' },
                { text: 'Custom Field1', value: '{CUSTOM1}' },
                { text: 'Custom Field2', value: '{CUSTOM2}' },
                { text: 'Custom Field3', value: '{CUSTOM3}' },
                { text: 'Custom Field4', value: '{CUSTOM4}' },
                { text: 'Custom Field5', value: '{CUSTOM5}' },
                { text: 'Custom Field6', value: '{CUSTOM6}' },
                { text: 'Custom Field7', value: '{CUSTOM7}' },
                { text: 'Custom Field15', value: '{CUSTOM15}' },
                { text: 'Custom Field16', value: '{CUSTOM16}' },
                { text: 'Custom Field17', value: '{CUSTOM17}' },
                { text: 'Custom Field18', value: '{CUSTOM18}' }
            ];
        }

        editor.addButton('custom_dropdown', {
            type: 'listbox',
            text: 'Insert Placeholder',
            icon: false,
            onselect: function() {
                editor.insertContent(this.value());
                this.value(null); // reset after selection
            },
            values: dynamicValues
        });
    });
})();
