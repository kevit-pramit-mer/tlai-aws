/**
 * Created by akshay on 10/10/17.
 */


(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.dynamicKeys", {
        _create: function () {
            var self = this;
            $.proxy(self.checkKeys0, self)();
            $.proxy(self.checkKeys1, self)();
            $.proxy(self.checkKeys2, self)();
            $.proxy(self.checkKeys3, self)();
            $.proxy(self.checkKeys4, self)();
            $.proxy(self.checkKeys5, self)();
            $.proxy(self.checkKeys6, self)();
            $.proxy(self.checkKeys7, self)();
            $.proxy(self.checkKeys8, self)();
            $.proxy(self.checkKeys9, self)();
            $.proxy(self.checkKeysStar, self)();
            $.proxy(self.checkKeysHash, self)();
            $.proxy(self.checkKeys0Load, self)();
            $.proxy(self.checkKeys1Load, self)();
            $.proxy(self.checkKeys2Load, self)();
            $.proxy(self.checkKeys3Load, self)();
            $.proxy(self.checkKeys4Load, self)();
            $.proxy(self.checkKeys5Load, self)();
            $.proxy(self.checkKeys6Load, self)();
            $.proxy(self.checkKeys7Load, self)();
            $.proxy(self.checkKeys8Load, self)();
            $.proxy(self.checkKeys9Load, self)();
            $.proxy(self.checkKeysStarLoad, self)();
            $.proxy(self.checkKeysHashLoad, self)();

        },
        checkKeys0: function () {
            var self = this;

            self.dropDownAudio0 = $('#dropDownFieldAudio_0', this.element);
            self.dropDownExtension0 = $('#dropDownFieldExtension_0', this.element);
            self.textField0 = $('#textField_0', this.element);
            self.textFieldButton0 = $('#textFieldWithButton_0', this.element);
            self.dropDownVExtension0 = $('#dropDownFieldVoicemailExtension_0', this.element);
            self.dropDownUserGroups0 = $('#dropDownFieldUserGroups_0', this.element);

            self.dropDownFieldTransferExtentions0 = $('#dropDownFieldTransferExtentions_0', this.element);
            self.dropDownFieldQueues0 = $('#dropDownFieldQueues_0', this.element);
            self.dropDownFieldRingGroups0 = $('#dropDownFieldRingGroups_0', this.element);
            self.dropDownFieldCopySubmenu0 = $('#dropDownFieldCopySubmenu_0', this.element);
            self.dropDownFieldConference0 = $('#dropDownFieldConference_0', this.element);
            self.dropDownFieldAudiotext0 = $('#dropDownFieldAudiotext_0', this.element);
            self.dropDownFieldVoicemail0 = $('#dropDownFieldVoicemail_0', this.element);

            self.dropDownAudio0.css('display', 'none');
            self.dropDownExtension0.css('display', 'none');
            self.textField0.css('display', 'none');
            self.textFieldButton0.css('display', 'none');
            self.dropDownVExtension0.css('display', 'none');
            self.dropDownUserGroups0.css('display', 'none');

            self.dropDownFieldTransferExtentions0.css('display', 'none');
            self.dropDownFieldQueues0.css('display', 'none');
            self.dropDownFieldRingGroups0.css('display', 'none');
            self.dropDownFieldCopySubmenu0.css('display', 'none');
            self.dropDownFieldConference0.css('display', 'none');
            self.dropDownFieldAudiotext0.css('display', 'none');
            self.dropDownFieldVoicemail0.css('display', 'none');

            $('#actions_0').on('change', function () {

                self.dropDownAudio0.css('display', 'none');
                self.dropDownExtension0.css('display', 'none');
                self.textField0.css('display', 'none');
                self.textFieldButton0.css('display', 'none');
                self.dropDownVExtension0.css('display', 'none');
                self.dropDownUserGroups0.css('display', 'none');
                self.dropDownFieldTransferExtentions0.css('display', 'none');
                self.dropDownFieldQueues0.css('display', 'none');
                self.dropDownFieldRingGroups0.css('display', 'none');
                self.dropDownFieldCopySubmenu0.css('display', 'none');
                self.dropDownFieldConference0.css('display', 'none');
                self.dropDownFieldAudiotext0.css('display', 'none');
                self.dropDownFieldVoicemail0.css('display', 'none');

                var value = $('#actions_0').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio0.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions0.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues0.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups0.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField0.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension0.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension0.css('display', 'block');
                        break;
                    case 'Sub Menu':
                        self.textFieldButton0.css('display', 'block');
                        break;
                    case 'Dial by name within User Group':
                        self.dropDownUserGroups0.css('display', 'block');
                        break;
                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu0.css('display', 'block');
                        break;
                    case 'Conference':
                        self.dropDownFieldConference0.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext0.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail0.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys1: function () {
            var self = this;

            self.dropDownAudio1 = $('#dropDownFieldAudio_1', this.element);
            self.dropDownExtension1 = $('#dropDownFieldExtension_1', this.element);
            self.textField1 = $('#textField_1', this.element);
            self.textFieldButton1 = $('#textFieldWithButton_1', this.element);
            self.dropDownVExtension1 = $('#dropDownFieldVoicemailExtension_1', this.element);
            self.dropDownUserGroups1 = $('#dropDownFieldUserGroups_1', this.element);
            self.dropDownFieldCopySubmenu1 = $('#dropDownFieldCopySubmenu_1', this.element);
            self.dropDownFieldConference1 = $('#dropDownFieldConference_1', this.element);
            self.dropDownFieldAudiotext1 = $('#dropDownFieldAudiotext_1', this.element);
            self.dropDownFieldVoicemail1 = $('#dropDownFieldVoicemail_1', this.element);

            self.dropDownAudio1.css('display', 'none');
            self.dropDownExtension1.css('display', 'none');
            self.textField1.css('display', 'none');
            self.textFieldButton1.css('display', 'none');
            self.dropDownVExtension1.css('display', 'none');
            self.dropDownUserGroups1.css('display', 'none');

            self.dropDownFieldTransferExtentions1 = $('#dropDownFieldTransferExtentions_1', this.element);
            self.dropDownFieldQueues1 = $('#dropDownFieldQueues_1', this.element);
            self.dropDownFieldRingGroups1 = $('#dropDownFieldRingGroups_1', this.element);

            self.dropDownFieldTransferExtentions1.css('display', 'none');
            self.dropDownFieldQueues1.css('display', 'none');
            self.dropDownFieldRingGroups1.css('display', 'none');
            self.dropDownFieldCopySubmenu1.css('display', 'none');

            self.dropDownFieldConference1.css('display', 'none');
            self.dropDownFieldAudiotext1.css('display', 'none');
            self.dropDownFieldVoicemail1.css('display', 'none');

            $('#actions_1').on('change', function () {

                self.dropDownAudio1.css('display', 'none');
                self.dropDownExtension1.css('display', 'none');
                self.textField1.css('display', 'none');
                self.textFieldButton1.css('display', 'none');
                self.dropDownVExtension1.css('display', 'none');
                self.dropDownUserGroups1.css('display', 'none');
                self.dropDownFieldTransferExtentions1.css('display', 'none');
                self.dropDownFieldQueues1.css('display', 'none');
                self.dropDownFieldRingGroups1.css('display', 'none');
                self.dropDownFieldCopySubmenu1.css('display', 'none');
                self.dropDownFieldConference1.css('display', 'none');
                self.dropDownFieldAudiotext1.css('display', 'none');
                self.dropDownFieldVoicemail1.css('display', 'none');

                var value = $('#actions_1').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio1.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions1.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues1.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups1.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField1.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension1.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension1.css('display', 'block');
                        break;
                    case 'Sub Menu':
                        self.textFieldButton1.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups1.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu1.css('display', 'block');
                        break;

                    case 'Conference':
                        self.dropDownFieldConference1.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext1.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail1.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys2: function () {
            var self = this;

            self.dropDownAudio2 = $('#dropDownFieldAudio_2', this.element);
            self.dropDownExtension2 = $('#dropDownFieldExtension_2', this.element);
            self.textField2 = $('#textField_2', this.element);
            self.textFieldButton2 = $('#textFieldWithButton_2', this.element);
            self.dropDownVExtension2 = $('#dropDownFieldVoicemailExtension_2', this.element);
            self.dropDownUserGroups2 = $('#dropDownFieldUserGroups_2', this.element);
            self.dropDownFieldCopySubmenu2 = $('#dropDownFieldCopySubmenu_2', this.element);
            self.dropDownFieldConference2 = $('#dropDownFieldConference_2', this.element);
            self.dropDownFieldAudiotext2 = $('#dropDownFieldAudiotext_2', this.element);
            self.dropDownFieldVoicemail2 = $('#dropDownFieldVoicemail_2', this.element);

            self.dropDownAudio2.css('display', 'none');
            self.dropDownExtension2.css('display', 'none');
            self.textField2.css('display', 'none');
            self.textFieldButton2.css('display', 'none');
            self.dropDownVExtension2.css('display', 'none');
            self.dropDownUserGroups2.css('display', 'none');

            self.dropDownFieldTransferExtentions2 = $('#dropDownFieldTransferExtentions_2', this.element);
            self.dropDownFieldQueues2 = $('#dropDownFieldQueues_2', this.element);
            self.dropDownFieldRingGroups2 = $('#dropDownFieldRingGroups_2', this.element);

            self.dropDownFieldTransferExtentions2.css('display', 'none');
            self.dropDownFieldQueues2.css('display', 'none');
            self.dropDownFieldRingGroups2.css('display', 'none');
            self.dropDownFieldCopySubmenu2.css('display', 'none');
            self.dropDownFieldConference2.css('display', 'none');
            self.dropDownFieldAudiotext2.css('display', 'none');
            self.dropDownFieldVoicemail2.css('display', 'none');

            $('#actions_2').on('change', function () {

                self.dropDownAudio2.css('display', 'none');
                self.dropDownExtension2.css('display', 'none');
                self.textField2.css('display', 'none');
                self.textFieldButton2.css('display', 'none');
                self.dropDownVExtension2.css('display', 'none');
                self.dropDownUserGroups2.css('display', 'none');
                self.dropDownFieldTransferExtentions2.css('display', 'none');
                self.dropDownFieldQueues2.css('display', 'none');
                self.dropDownFieldRingGroups2.css('display', 'none');
                self.dropDownFieldCopySubmenu2.css('display', 'none');
                self.dropDownFieldConference2.css('display', 'none');
                self.dropDownFieldAudiotext2.css('display', 'none');
                self.dropDownFieldVoicemail2.css('display', 'none');

                var value = $('#actions_2').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio2.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions2.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues2.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups2.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField2.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension2.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension2.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton2.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups2.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu2.css('display', 'block');
                        break;

                    case 'Conference':
                        self.dropDownFieldConference2.css('display', 'block');
                        break;

                    case 'IVR':
                        self.dropDownFieldAudiotext2.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail2.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys3: function () {

            var self = this;

            self.dropDownAudio3 = $('#dropDownFieldAudio_3', this.element);
            self.dropDownExtension3 = $('#dropDownFieldExtension_3', this.element);
            self.textField3 = $('#textField_3', this.element);
            self.textFieldButton3 = $('#textFieldWithButton_3', this.element);
            self.dropDownVExtension3 = $('#dropDownFieldVoicemailExtension_3', this.element);
            self.dropDownUserGroups3 = $('#dropDownFieldUserGroups_3', this.element);
            self.dropDownFieldCopySubmenu3 = $('#dropDownFieldCopySubmenu_3', this.element);
            self.dropDownFieldConference3 = $('#dropDownFieldConference_3', this.element);
            self.dropDownFieldAudiotext3 = $('#dropDownFieldAudiotext_3', this.element);
            self.dropDownFieldVoicemail3 = $('#dropDownFieldVoicemail_3', this.element);

            self.dropDownAudio3.css('display', 'none');
            self.dropDownExtension3.css('display', 'none');
            self.textField3.css('display', 'none');
            self.textFieldButton3.css('display', 'none');
            self.dropDownVExtension3.css('display', 'none');
            self.dropDownUserGroups3.css('display', 'none');

            self.dropDownFieldTransferExtentions3 = $('#dropDownFieldTransferExtentions_3', this.element);
            self.dropDownFieldQueues3 = $('#dropDownFieldQueues_3', this.element);
            self.dropDownFieldRingGroups3 = $('#dropDownFieldRingGroups_3', this.element);

            self.dropDownFieldTransferExtentions3.css('display', 'none');
            self.dropDownFieldQueues3.css('display', 'none');
            self.dropDownFieldRingGroups3.css('display', 'none');
            self.dropDownFieldCopySubmenu3.css('display', 'none');
            self.dropDownFieldConference3.css('display', 'none');
            self.dropDownFieldAudiotext3.css('display', 'none');
            self.dropDownFieldVoicemail3.css('display', 'none');

            $('#actions_3').on('change', function () {

                self.dropDownAudio3.css('display', 'none');
                self.dropDownExtension3.css('display', 'none');
                self.textField3.css('display', 'none');
                self.textFieldButton3.css('display', 'none');
                self.dropDownVExtension3.css('display', 'none');
                self.dropDownUserGroups3.css('display', 'none');
                self.dropDownFieldTransferExtentions3.css('display', 'none');
                self.dropDownFieldQueues3.css('display', 'none');
                self.dropDownFieldRingGroups3.css('display', 'none');
                self.dropDownFieldCopySubmenu3.css('display', 'none');
                self.dropDownFieldConference3.css('display', 'none');
                self.dropDownFieldAudiotext3.css('display', 'none');
                self.dropDownFieldVoicemail3.css('display', 'none');

                var value = $('#actions_3').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio3.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions3.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues3.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups3.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField3.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension3.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension3.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton3.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups3.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu3.css('display', 'block');
                        break;

                    case 'Conference':
                        self.dropDownFieldConference3.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext3.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail3.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys4: function () {
            var self = this;

            self.dropDownAudio4 = $('#dropDownFieldAudio_4', this.element);
            self.dropDownExtension4 = $('#dropDownFieldExtension_4', this.element);
            self.textField4 = $('#textField_4', this.element);
            self.textFieldButton4 = $('#textFieldWithButton_4', this.element);
            self.dropDownVExtension4 = $('#dropDownFieldVoicemailExtension_4', this.element);
            self.dropDownUserGroups4 = $('#dropDownFieldUserGroups_4', this.element);
            self.dropDownFieldCopySubmenu4 = $('#dropDownFieldCopySubmenu_4', this.element);
            self.dropDownFieldConference4 = $('#dropDownFieldConference_4', this.element);
            self.dropDownFieldAudiotext4 = $('#dropDownFieldAudiotext_4', this.element);
            self.dropDownFieldVoicemail4 = $('#dropDownFieldVoicemail_4', this.element);

            self.dropDownAudio4.css('display', 'none');
            self.dropDownExtension4.css('display', 'none');
            self.textField4.css('display', 'none');
            self.textFieldButton4.css('display', 'none');
            self.dropDownVExtension4.css('display', 'none');
            self.dropDownUserGroups4.css('display', 'none');

            self.dropDownFieldTransferExtentions4 = $('#dropDownFieldTransferExtentions_4', this.element);
            self.dropDownFieldQueues4 = $('#dropDownFieldQueues_4', this.element);
            self.dropDownFieldRingGroups4 = $('#dropDownFieldRingGroups_4', this.element);

            self.dropDownFieldTransferExtentions4.css('display', 'none');
            self.dropDownFieldQueues4.css('display', 'none');
            self.dropDownFieldRingGroups4.css('display', 'none');
            self.dropDownFieldCopySubmenu4.css('display', 'none');

            self.dropDownFieldConference4.css('display', 'none');
            self.dropDownFieldAudiotext4.css('display', 'none');
            self.dropDownFieldVoicemail4.css('display', 'none');

            $('#actions_4').on('change', function () {

                self.dropDownAudio4.css('display', 'none');
                self.dropDownExtension4.css('display', 'none');
                self.textField4.css('display', 'none');
                self.textFieldButton4.css('display', 'none');
                self.dropDownVExtension4.css('display', 'none');
                self.dropDownUserGroups4.css('display', 'none');
                self.dropDownFieldTransferExtentions4.css('display', 'none');
                self.dropDownFieldQueues4.css('display', 'none');
                self.dropDownFieldRingGroups4.css('display', 'none');
                self.dropDownFieldCopySubmenu4.css('display', 'none');
                self.dropDownFieldConference4.css('display', 'none');
                self.dropDownFieldAudiotext4.css('display', 'none');
                self.dropDownFieldVoicemail4.css('display', 'none');

                var value = $('#actions_4').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio4.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions4.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues4.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups4.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField4.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension4.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension4.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton4.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups4.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu4.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference4.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext4.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail4.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys5: function () {
            var self = this;

            self.dropDownAudio5 = $('#dropDownFieldAudio_5', this.element);
            self.dropDownExtension5 = $('#dropDownFieldExtension_5', this.element);
            self.textField5 = $('#textField_5', this.element);
            self.textFieldButton5 = $('#textFieldWithButton_5', this.element);
            self.dropDownVExtension5 = $('#dropDownFieldVoicemailExtension_5', this.element);
            self.dropDownUserGroups5 = $('#dropDownFieldUserGroups_5', this.element);
            self.dropDownFieldCopySubmenu5 = $('#dropDownFieldCopySubmenu_5', this.element);
            self.dropDownFieldConference5 = $('#dropDownFieldConference_5', this.element);
            self.dropDownFieldAudiotext5 = $('#dropDownFieldAudiotext_5', this.element);
            self.dropDownFieldVoicemail5 = $('#dropDownFieldVoicemail_5', this.element);

            self.dropDownAudio5.css('display', 'none');
            self.dropDownExtension5.css('display', 'none');
            self.textField5.css('display', 'none');
            self.textFieldButton5.css('display', 'none');
            self.dropDownVExtension5.css('display', 'none');
            self.dropDownUserGroups5.css('display', 'none');

            self.dropDownFieldTransferExtentions5 = $('#dropDownFieldTransferExtentions_5', this.element);
            self.dropDownFieldQueues5 = $('#dropDownFieldQueues_5', this.element);
            self.dropDownFieldRingGroups5 = $('#dropDownFieldRingGroups_5', this.element);

            self.dropDownFieldTransferExtentions5.css('display', 'none');
            self.dropDownFieldQueues5.css('display', 'none');
            self.dropDownFieldRingGroups5.css('display', 'none');
            self.dropDownFieldCopySubmenu5.css('display', 'none');
            self.dropDownFieldConference5.css('display', 'none');
            self.dropDownFieldAudiotext5.css('display', 'none');
            self.dropDownFieldVoicemail5.css('display', 'none');

            $('#actions_5').on('change', function () {

                self.dropDownAudio5.css('display', 'none');
                self.dropDownExtension5.css('display', 'none');
                self.textField5.css('display', 'none');
                self.textFieldButton5.css('display', 'none');
                self.dropDownVExtension5.css('display', 'none');
                self.dropDownUserGroups5.css('display', 'none');
                self.dropDownFieldTransferExtentions5.css('display', 'none');
                self.dropDownFieldQueues5.css('display', 'none');
                self.dropDownFieldRingGroups5.css('display', 'none');
                self.dropDownFieldCopySubmenu5.css('display', 'none');
                self.dropDownFieldConference5.css('display', 'none');
                self.dropDownFieldAudiotext5.css('display', 'none');
                self.dropDownFieldVoicemail5.css('display', 'none');

                var value = $('#actions_5').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio5.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions5.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues5.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups5.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField5.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension5.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension5.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton5.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups5.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu5.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference5.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext5.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail5.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys6: function () {
            var self = this;

            self.dropDownAudio6 = $('#dropDownFieldAudio_6', this.element);
            self.dropDownExtension6 = $('#dropDownFieldExtension_6', this.element);
            self.textField6 = $('#textField_6', this.element);
            self.textFieldButton6 = $('#textFieldWithButton_6', this.element);
            self.dropDownVExtension6 = $('#dropDownFieldVoicemailExtension_6', this.element);
            self.dropDownUserGroups6 = $('#dropDownFieldUserGroups_6', this.element);
            self.dropDownFieldCopySubmenu6 = $('#dropDownFieldCopySubmenu_6', this.element);
            self.dropDownFieldConference6 = $('#dropDownFieldConference_6', this.element);
            self.dropDownFieldAudiotext6 = $('#dropDownFieldAudiotext_6', this.element);
            self.dropDownFieldVoicemail6 = $('#dropDownFieldVoicemail_6', this.element);

            self.dropDownAudio6.css('display', 'none');
            self.dropDownExtension6.css('display', 'none');
            self.textField6.css('display', 'none');
            self.textFieldButton6.css('display', 'none');
            self.dropDownVExtension6.css('display', 'none');
            self.dropDownUserGroups6.css('display', 'none');

            self.dropDownFieldTransferExtentions6 = $('#dropDownFieldTransferExtentions_6', this.element);
            self.dropDownFieldQueues6 = $('#dropDownFieldQueues_6', this.element);
            self.dropDownFieldRingGroups6 = $('#dropDownFieldRingGroups_6', this.element);

            self.dropDownFieldTransferExtentions6.css('display', 'none');
            self.dropDownFieldQueues6.css('display', 'none');
            self.dropDownFieldRingGroups6.css('display', 'none');
            self.dropDownFieldCopySubmenu6.css('display', 'none');
            self.dropDownFieldConference6.css('display', 'none');
            self.dropDownFieldAudiotext6.css('display', 'none');
            self.dropDownFieldVoicemail6.css('display', 'none');

            $('#actions_6').on('change', function () {

                self.dropDownAudio6.css('display', 'none');
                self.dropDownExtension6.css('display', 'none');
                self.textField6.css('display', 'none');
                self.textFieldButton6.css('display', 'none');
                self.dropDownVExtension6.css('display', 'none');
                self.dropDownUserGroups6.css('display', 'none');
                self.dropDownFieldTransferExtentions6.css('display', 'none');
                self.dropDownFieldQueues6.css('display', 'none');
                self.dropDownFieldRingGroups6.css('display', 'none');
                self.dropDownFieldCopySubmenu6.css('display', 'none');
                self.dropDownFieldConference6.css('display', 'none');
                self.dropDownFieldAudiotext6.css('display', 'none');
                self.dropDownFieldVoicemail6.css('display', 'none');

                var value = $('#actions_6').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio6.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions6.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues6.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups6.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField6.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension6.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension6.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton6.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups6.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu6.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference6.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext6.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail6.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys7: function () {

            var self = this;

            self.dropDownAudio7 = $('#dropDownFieldAudio_7', this.element);
            self.dropDownExtension7 = $('#dropDownFieldExtension_7', this.element);
            self.textField7 = $('#textField_7', this.element);
            self.textFieldButton7 = $('#textFieldWithButton_7', this.element);
            self.dropDownVExtension7 = $('#dropDownFieldVoicemailExtension_7', this.element);
            self.dropDownUserGroups7 = $('#dropDownFieldUserGroups_7', this.element);
            self.dropDownFieldCopySubmenu7 = $('#dropDownFieldCopySubmenu_7', this.element);
            self.dropDownFieldConference7 = $('#dropDownFieldConference_7', this.element);
            self.dropDownFieldAudiotext7 = $('#dropDownFieldAudiotext_7', this.element);
            self.dropDownFieldVoicemail7 = $('#dropDownFieldVoicemail_7', this.element);

            self.dropDownAudio7.css('display', 'none');
            self.dropDownExtension7.css('display', 'none');
            self.textField7.css('display', 'none');
            self.textFieldButton7.css('display', 'none');
            self.dropDownVExtension7.css('display', 'none');
            self.dropDownUserGroups7.css('display', 'none');

            self.dropDownFieldTransferExtentions7 = $('#dropDownFieldTransferExtentions_7', this.element);
            self.dropDownFieldQueues7 = $('#dropDownFieldQueues_7', this.element);
            self.dropDownFieldRingGroups7 = $('#dropDownFieldRingGroups_7', this.element);

            self.dropDownFieldTransferExtentions7.css('display', 'none');
            self.dropDownFieldQueues7.css('display', 'none');
            self.dropDownFieldRingGroups7.css('display', 'none');
            self.dropDownFieldCopySubmenu7.css('display', 'none');
            self.dropDownFieldConference7.css('display', 'none');
            self.dropDownFieldAudiotext7.css('display', 'none');
            self.dropDownFieldVoicemail7.css('display', 'none');

            $('#actions_7').on('change', function () {

                self.dropDownAudio7.css('display', 'none');
                self.dropDownExtension7.css('display', 'none');
                self.textField7.css('display', 'none');
                self.textFieldButton7.css('display', 'none');
                self.dropDownVExtension7.css('display', 'none');
                self.dropDownUserGroups7.css('display', 'none');
                self.dropDownFieldTransferExtentions7.css('display', 'none');
                self.dropDownFieldQueues7.css('display', 'none');
                self.dropDownFieldRingGroups7.css('display', 'none');
                self.dropDownFieldConference7.css('display', 'none');
                self.dropDownFieldAudiotext7.css('display', 'none');
                self.dropDownFieldVoicemail7.css('display', 'none');

                var value = $('#actions_7').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio7.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions7.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues7.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups7.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField7.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension7.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension7.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton7.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups7.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu7.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference7.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext7.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail7.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys8: function () {

            var self = this;

            self.dropDownAudio8 = $('#dropDownFieldAudio_8', this.element);
            self.dropDownExtension8 = $('#dropDownFieldExtension_8', this.element);
            self.textField8 = $('#textField_8', this.element);
            self.textFieldButton8 = $('#textFieldWithButton_8', this.element);
            self.dropDownVExtension8 = $('#dropDownFieldVoicemailExtension_8', this.element);
            self.dropDownUserGroups8 = $('#dropDownFieldUserGroups_8', this.element);
            self.dropDownFieldCopySubmenu8 = $('#dropDownFieldCopySubmenu_8', this.element);
            self.dropDownFieldConference8 = $('#dropDownFieldConference_8', this.element);
            self.dropDownFieldAudiotext8 = $('#dropDownFieldAudiotext_8', this.element);
            self.dropDownFieldVoicemail8 = $('#dropDownFieldVoicemail_8', this.element);

            self.dropDownAudio8.css('display', 'none');
            self.dropDownExtension8.css('display', 'none');
            self.textField8.css('display', 'none');
            self.textFieldButton8.css('display', 'none');
            self.dropDownVExtension8.css('display', 'none');
            self.dropDownUserGroups8.css('display', 'none');

            self.dropDownFieldTransferExtentions8 = $('#dropDownFieldTransferExtentions_8', this.element);
            self.dropDownFieldQueues8 = $('#dropDownFieldQueues_8', this.element);
            self.dropDownFieldRingGroups8 = $('#dropDownFieldRingGroups_8', this.element);

            self.dropDownFieldTransferExtentions8.css('display', 'none');
            self.dropDownFieldQueues8.css('display', 'none');
            self.dropDownFieldRingGroups8.css('display', 'none');
            self.dropDownFieldCopySubmenu8.css('display', 'none');
            self.dropDownFieldConference8.css('display', 'none');
            self.dropDownFieldAudiotext8.css('display', 'none');
            self.dropDownFieldVoicemail8.css('display', 'none');

            $('#actions_8').on('change', function () {

                self.dropDownAudio8.css('display', 'none');
                self.dropDownExtension8.css('display', 'none');
                self.textField8.css('display', 'none');
                self.textFieldButton8.css('display', 'none');
                self.dropDownVExtension8.css('display', 'none');
                self.dropDownUserGroups8.css('display', 'none');
                self.dropDownFieldTransferExtentions8.css('display', 'none');
                self.dropDownFieldQueues8.css('display', 'none');
                self.dropDownFieldRingGroups8.css('display', 'none');
                self.dropDownFieldCopySubmenu8.css('display', 'none');
                self.dropDownFieldConference8.css('display', 'none');
                self.dropDownFieldAudiotext8.css('display', 'none');
                self.dropDownFieldVoicemail8.css('display', 'none');

                var value = $('#actions_8').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio8.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions8.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues8.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups8.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField8.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension8.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension8.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton8.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups8.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu8.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference8.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext8.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail8.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeys9: function () {
            var self = this;

            self.dropDownAudio9 = $('#dropDownFieldAudio_9', this.element);
            self.dropDownExtension9 = $('#dropDownFieldExtension_9', this.element);
            self.textField9 = $('#textField_9', this.element);
            self.textFieldButton9 = $('#textFieldWithButton_9', this.element);
            self.dropDownVExtension9 = $('#dropDownFieldVoicemailExtension_9', this.element);
            self.dropDownUserGroups9 = $('#dropDownFieldUserGroups_9', this.element);
            self.dropDownFieldCopySubmenu9 = $('#dropDownFieldCopySubmenu_9', this.element);
            self.dropDownFieldConference9 = $('#dropDownFieldConference_9', this.element);
            self.dropDownFieldAudiotext9 = $('#dropDownFieldAudiotext_9', this.element);
            self.dropDownFieldVoicemail9 = $('#dropDownFieldVoicemail_9', this.element);

            self.dropDownAudio9.css('display', 'none');
            self.dropDownExtension9.css('display', 'none');
            self.textField9.css('display', 'none');
            self.textFieldButton9.css('display', 'none');
            self.dropDownVExtension9.css('display', 'none');
            self.dropDownUserGroups9.css('display', 'none');

            self.dropDownFieldTransferExtentions9 = $('#dropDownFieldTransferExtentions_9', this.element);
            self.dropDownFieldQueues9 = $('#dropDownFieldQueues_9', this.element);
            self.dropDownFieldRingGroups9 = $('#dropDownFieldRingGroups_9', this.element);

            self.dropDownFieldTransferExtentions9.css('display', 'none');
            self.dropDownFieldQueues9.css('display', 'none');
            self.dropDownFieldRingGroups9.css('display', 'none');
            self.dropDownFieldCopySubmenu9.css('display', 'none');
            self.dropDownFieldConference9.css('display', 'none');
            self.dropDownFieldAudiotext9.css('display', 'none');
            self.dropDownFieldVoicemail9.css('display', 'none');

            $('#actions_9').on('change', function () {

                self.dropDownAudio9.css('display', 'none');
                self.dropDownExtension9.css('display', 'none');
                self.textField9.css('display', 'none');
                self.textFieldButton9.css('display', 'none');
                self.dropDownVExtension9.css('display', 'none');
                self.dropDownUserGroups9.css('display', 'none');
                self.dropDownFieldTransferExtentions9.css('display', 'none');
                self.dropDownFieldQueues9.css('display', 'none');
                self.dropDownFieldRingGroups9.css('display', 'none');
                self.dropDownFieldCopySubmenu9.css('display', 'none');
                self.dropDownFieldConference9.css('display', 'none');
                self.dropDownFieldAudiotext9.css('display', 'none');
                self.dropDownFieldVoicemail9.css('display', 'none');

                var value = $('#actions_9').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudio9.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentions9.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueues9.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroups9.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textField9.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtension9.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtension9.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButton9.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroups9.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenu9.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConference9.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotext9.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemail9.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeysStar: function () {
            var self = this;

            self.dropDownAudioStar = $('#dropDownFieldAudio_star', this.element);
            self.dropDownExtensionStar = $('#dropDownFieldExtension_star', this.element);
            self.textFieldStar = $('#textField_star', this.element);
            self.textFieldButtonStar = $('#textFieldWithButton_star', this.element);
            self.dropDownVExtensionStar = $('#dropDownFieldVoicemailExtension_star', this.element);
            self.dropDownUserGroupsStar = $('#dropDownFieldUserGroups_star', this.element);
            self.dropDownFieldCopySubmenuStar = $('#dropDownFieldCopySubmenu_star', this.element);
            self.dropDownFieldConferenceStar = $('#dropDownFieldConference_star', this.element);
            self.dropDownFieldAudiotextStar = $('#dropDownFieldAudiotext_star', this.element);
            self.dropDownFieldVoicemailStar = $('#dropDownFieldVoicemail_star', this.element);

            self.dropDownAudioStar.css('display', 'none');
            self.dropDownExtensionStar.css('display', 'none');
            self.textFieldStar.css('display', 'none');
            self.textFieldButtonStar.css('display', 'none');
            self.dropDownVExtensionStar.css('display', 'none');
            self.dropDownUserGroupsStar.css('display', 'none');

            self.dropDownFieldTransferExtentionsStar = $('#dropDownFieldTransferExtentions_star', this.element);
            self.dropDownFieldQueuesStar = $('#dropDownFieldQueues_star', this.element);
            self.dropDownFieldRingGroupsStar = $('#dropDownFieldRingGroups_star', this.element);

            self.dropDownFieldTransferExtentionsStar.css('display', 'none');
            self.dropDownFieldQueuesStar.css('display', 'none');
            self.dropDownFieldRingGroupsStar.css('display', 'none');
            self.dropDownFieldCopySubmenuStar.css('display', 'none');
            self.dropDownFieldConferenceStar.css('display', 'none');
            self.dropDownFieldAudiotextStar.css('display', 'none');
            self.dropDownFieldVoicemailStar.css('display', 'none');

            $('#actions_star').on('change', function () {

                self.dropDownAudioStar.css('display', 'none');
                self.dropDownExtensionStar.css('display', 'none');
                self.textFieldStar.css('display', 'none');
                self.textFieldButtonStar.css('display', 'none');
                self.dropDownVExtensionStar.css('display', 'none');
                self.dropDownUserGroupsStar.css('display', 'none');
                self.dropDownFieldTransferExtentionsStar.css('display', 'none');
                self.dropDownFieldQueuesStar.css('display', 'none');
                self.dropDownFieldRingGroupsStar.css('display', 'none');
                self.dropDownFieldConferenceStar.css('display', 'none');
                self.dropDownFieldAudiotextStar.css('display', 'none');
                self.dropDownFieldVoicemailStar.css('display', 'none');

                var value = $('#actions_star').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudioStar.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentionsStar.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueuesStar.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroupsStar.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textFieldStar.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtensionStar.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtensionStar.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButtonStar.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroupsStar.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenuStar.css('display', 'block');
                        break;


                    case 'Conference':
                        self.dropDownFieldConferenceStar.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotextStar.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemailStar.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })
        },
        checkKeysHash: function () {
            var self = this;

            self.dropDownAudioHash = $('#dropDownFieldAudio_hash', this.element);
            self.dropDownExtensionHash = $('#dropDownFieldExtension_hash', this.element);
            self.textFieldHash = $('#textField_hash', this.element);
            self.textFieldButtonHash = $('#textFieldWithButton_hash', this.element);
            self.dropDownVExtensionHash = $('#dropDownFieldVoicemailExtension_hash', this.element);
            self.dropDownUserGroupsHash = $('#dropDownFieldUserGroups_hash', this.element);
            self.dropDownFieldCopySubmenuHash = $('#dropDownFieldCopySubmenu_hash', this.element);
            self.dropDownFieldConferenceHash = $('#dropDownFieldConference_hash', this.element);
            self.dropDownFieldAudiotextHash = $('#dropDownFieldAudiotext_hash', this.element);
            self.dropDownFieldVoicemailHash = $('#dropDownFieldVoicemail_hash', this.element);

            self.dropDownAudioHash.css('display', 'none');
            self.dropDownExtensionHash.css('display', 'none');
            self.textFieldHash.css('display', 'none');
            self.textFieldButtonHash.css('display', 'none');
            self.dropDownVExtensionHash.css('display', 'none');
            self.dropDownUserGroupsHash.css('display', 'none');

            self.dropDownFieldTransferExtentionsHash = $('#dropDownFieldTransferExtentions_hash', this.element);
            self.dropDownFieldQueuesHash = $('#dropDownFieldQueues_hash', this.element);
            self.dropDownFieldRingGroupsHash = $('#dropDownFieldRingGroups_hash', this.element);

            self.dropDownFieldTransferExtentionsHash.css('display', 'none');
            self.dropDownFieldQueuesHash.css('display', 'none');
            self.dropDownFieldRingGroupsHash.css('display', 'none');
            self.dropDownFieldCopySubmenuHash.css('display', 'none');
            self.dropDownFieldConferenceHash.css('display', 'none');
            self.dropDownFieldAudiotextHash.css('display', 'none');
            self.dropDownFieldVoicemailHash.css('display', 'none');

            $('#actions_hash').on('change', function () {

                self.dropDownAudioHash.css('display', 'none');
                self.dropDownExtensionHash.css('display', 'none');
                self.textFieldHash.css('display', 'none');
                self.textFieldButtonHash.css('display', 'none');
                self.dropDownVExtensionHash.css('display', 'none');
                self.dropDownUserGroupsHash.css('display', 'none');
                self.dropDownFieldTransferExtentionsHash.css('display', 'none');
                self.dropDownFieldQueuesHash.css('display', 'none');
                self.dropDownFieldRingGroupsHash.css('display', 'none');
                self.dropDownFieldCopySubmenuHash.css('display', 'none');
                self.dropDownFieldConferenceHash.css('display', 'none');
                self.dropDownFieldAudiotextHash.css('display', 'none');
                self.dropDownFieldVoicemailHash.css('display', 'none');

                var value = $('#actions_hash').find(":selected").val();

                switch (value) {
                    case 'Playfile':
                        self.dropDownAudioHash.css('display', 'block');
                        break;
                    case 'Transfer to extension':
                        self.dropDownFieldTransferExtentionsHash.css('display', 'block');
                        break;
                    case 'Queues':
                        self.dropDownFieldQueuesHash.css('display', 'block');
                        break;
                    case 'Ring Groups':
                        self.dropDownFieldRingGroupsHash.css('display', 'block');
                        break;
                    case 'External Number':
                        self.textFieldHash.css('display', 'block');
                        break;
                    case 'Deposit to user personal voicemail box':
                        self.dropDownExtensionHash.css('display', 'block');
                        break;
                    case 'Deposit to Common Voicemail box':
                        self.dropDownVExtensionHash.css('display', 'block');
                        break;

                    case 'Sub Menu':
                        self.textFieldButtonHash.css('display', 'block');
                        break;

                    case 'Dial by name within User Group':
                        self.dropDownUserGroupsHash.css('display', 'block');
                        break;

                    case 'Copy Sub Menu':
                        self.dropDownFieldCopySubmenuHash.css('display', 'block');
                        break;

                    case 'Conference':
                        self.dropDownFieldConferenceHash.css('display', 'block');
                        break;
                    case 'IVR':
                        self.dropDownFieldAudiotextHash.css('display', 'block');
                        break;
                    case 'Voicemail':
                        self.dropDownFieldVoicemailHash.css('display', 'block');
                        break;
                    case 'Return to Previous Menu':
                    case 'Dial by name':
                    case 'No Action':
                    case 'Repeat Menu':
                    case 'Disconnect':
                    case 'Login to voicemail box':
                    default :
                        break;
                }
            })

        },
        checkKeys0Load: function () {
            var self = this;

            self.dropDownAudio0 = $('#dropDownFieldAudio_0', this.element);
            self.dropDownExtension0 = $('#dropDownFieldExtension_0', this.element);
            self.textField0 = $('#textField_0', this.element);
            self.textFieldButton0 = $('#textFieldWithButton_0', this.element);
            self.dropDownVExtension0 = $('#dropDownFieldVoicemailExtension_0', this.element);
            self.dropDownUserGroups0 = $('#dropDownFieldUserGroups_0', this.element);
            self.dropDownFieldTransferExtentions0 = $('#dropDownFieldTransferExtentions_0', this.element);
            self.dropDownFieldQueues0 = $('#dropDownFieldQueues_0', this.element);
            self.dropDownFieldRingGroups0 = $('#dropDownFieldRingGroups_0', this.element);
            self.dropDownFieldCopySubmenu0 = $('#dropDownFieldCopySubmenu_0', this.element);
            self.dropDownFieldConference0 = $('#dropDownFieldConference_0', this.element);
            self.dropDownFieldAudiotext0 = $('#dropDownFieldAudiotext_0', this.element);
            self.dropDownFieldVoicemail0 = $('#dropDownFieldVoicemail_0', this.element);

            self.dropDownAudio0.css('display', 'none');
            self.dropDownExtension0.css('display', 'none');
            self.textField0.css('display', 'none');
            self.textFieldButton0.css('display', 'none');
            self.dropDownVExtension0.css('display', 'none');
            self.dropDownUserGroups0.css('display', 'none');
            self.dropDownFieldTransferExtentions0.css('display', 'none');
            self.dropDownFieldQueues0.css('display', 'none');
            self.dropDownFieldRingGroups0.css('display', 'none');
            self.dropDownFieldCopySubmenu0.css('display', 'none');
            self.dropDownFieldConference0.css('display', 'none');
            self.dropDownFieldAudiotext0.css('display', 'none');
            self.dropDownFieldVoicemail0.css('display', 'none');

            var value = $('#actions_0').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio0.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions0.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues0.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups0.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField0.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension0.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension0.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton0.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups0.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu0.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference0.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext0.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail0.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    self.dropDownAudio0.css('display', 'none');
                    self.dropDownExtension0.css('display', 'none');
                    self.textField0.css('display', 'none');
                    self.textFieldButton0.css('display', 'none');
                    self.dropDownVExtension0.css('display', 'none');
                    self.dropDownUserGroups0.css('display', 'none');
                    break;
            }
        },
        checkKeys1Load: function () {
            var self = this;

            self.dropDownAudio1 = $('#dropDownFieldAudio_1', this.element);
            self.dropDownExtension1 = $('#dropDownFieldExtension_1', this.element);
            self.textField1 = $('#textField_1', this.element);
            self.textFieldButton1 = $('#textFieldWithButton_1', this.element);
            self.dropDownVExtension1 = $('#dropDownFieldVoicemailExtension_1', this.element);
            self.dropDownUserGroups1 = $('#dropDownFieldUserGroups_1', this.element);
            self.dropDownFieldTransferExtentions1 = $('#dropDownFieldTransferExtentions_1', this.element);
            self.dropDownFieldQueues1 = $('#dropDownFieldQueues_1', this.element);
            self.dropDownFieldRingGroups1 = $('#dropDownFieldRingGroups_1', this.element);
            self.dropDownFieldCopySubmenu1 = $('#dropDownFieldCopySubmenu_1', this.element);
            self.dropDownFieldConference1 = $('#dropDownFieldConference_1', this.element);
            self.dropDownFieldAudiotext1 = $('#dropDownFieldAudiotext_1', this.element);
            self.dropDownFieldVoicemail1 = $('#dropDownFieldVoicemail_1', this.element);

            self.dropDownAudio1.css('display', 'none');
            self.dropDownExtension1.css('display', 'none');
            self.textField1.css('display', 'none');
            self.textFieldButton1.css('display', 'none');
            self.dropDownVExtension1.css('display', 'none');
            self.dropDownUserGroups1.css('display', 'none');
            self.dropDownFieldTransferExtentions1.css('display', 'none');
            self.dropDownFieldQueues1.css('display', 'none');
            self.dropDownFieldRingGroups1.css('display', 'none');
            self.dropDownFieldCopySubmenu1.css('display', 'none');
            self.dropDownFieldConference1.css('display', 'none');
            self.dropDownFieldAudiotext1.css('display', 'none');
            self.dropDownFieldVoicemail1.css('display', 'none');

            var value = $('#actions_1').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio1.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions1.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues1.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups1.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField1.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension1.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension1.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton1.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups1.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu1.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference1.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext1.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail1.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }

        },
        checkKeys2Load: function () {
            var self = this;

            self.dropDownAudio2 = $('#dropDownFieldAudio_2', this.element);
            self.dropDownExtension2 = $('#dropDownFieldExtension_2', this.element);
            self.textField2 = $('#textField_2', this.element);
            self.textFieldButton2 = $('#textFieldWithButton_2', this.element);
            self.dropDownVExtension2 = $('#dropDownFieldVoicemailExtension_2', this.element);
            self.dropDownUserGroups2 = $('#dropDownFieldUserGroups_2', this.element);
            self.dropDownFieldTransferExtentions2 = $('#dropDownFieldTransferExtentions_2', this.element);
            self.dropDownFieldQueues2 = $('#dropDownFieldQueues_2', this.element);
            self.dropDownFieldRingGroups2 = $('#dropDownFieldRingGroups_2', this.element);
            self.dropDownFieldCopySubmenu2 = $('#dropDownFieldCopySubmenu_2', this.element);
            self.dropDownFieldConference2 = $('#dropDownFieldConference_2', this.element);
            self.dropDownFieldAudiotext2 = $('#dropDownFieldAudiotext_2', this.element);
            self.dropDownFieldVoicemail2 = $('#dropDownFieldVoicemail_2', this.element);

            self.dropDownAudio2.css('display', 'none');
            self.dropDownExtension2.css('display', 'none');
            self.textField2.css('display', 'none');
            self.textFieldButton2.css('display', 'none');
            self.dropDownVExtension2.css('display', 'none');
            self.dropDownUserGroups2.css('display', 'none');
            self.dropDownFieldTransferExtentions2.css('display', 'none');
            self.dropDownFieldQueues2.css('display', 'none');
            self.dropDownFieldRingGroups2.css('display', 'none');
            self.dropDownFieldCopySubmenu2.css('display', 'none');
            self.dropDownFieldConference2.css('display', 'none');
            self.dropDownFieldAudiotext2.css('display', 'none');
            self.dropDownFieldVoicemail2.css('display', 'none');

            var value = $('#actions_2').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio2.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions2.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues2.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups2.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField2.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension2.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension2.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton2.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups2.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu2.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference2.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext2.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail2.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys3Load: function () {

            var self = this;

            self.dropDownAudio3 = $('#dropDownFieldAudio_3', this.element);
            self.dropDownExtension3 = $('#dropDownFieldExtension_3', this.element);
            self.textField3 = $('#textField_3', this.element);
            self.textFieldButton3 = $('#textFieldWithButton_3', this.element);
            self.dropDownVExtension3 = $('#dropDownFieldVoicemailExtension_3', this.element);
            self.dropDownUserGroups3 = $('#dropDownFieldUserGroups_3', this.element);
            self.dropDownFieldTransferExtentions3 = $('#dropDownFieldTransferExtentions_3', this.element);
            self.dropDownFieldQueues3 = $('#dropDownFieldQueues_3', this.element);
            self.dropDownFieldRingGroups3 = $('#dropDownFieldRingGroups_3', this.element);
            self.dropDownFieldCopySubmenu3 = $('#dropDownFieldCopySubmenu_3', this.element);
            self.dropDownFieldConference3 = $('#dropDownFieldConference_3', this.element);
            self.dropDownFieldAudiotext3 = $('#dropDownFieldAudiotext_3', this.element);
            self.dropDownFieldVoicemail3 = $('#dropDownFieldVoicemail_3', this.element);

            self.dropDownAudio3.css('display', 'none');
            self.dropDownExtension3.css('display', 'none');
            self.textField3.css('display', 'none');
            self.textFieldButton3.css('display', 'none');
            self.dropDownVExtension3.css('display', 'none');
            self.dropDownUserGroups3.css('display', 'none');
            self.dropDownFieldTransferExtentions3.css('display', 'none');
            self.dropDownFieldQueues3.css('display', 'none');
            self.dropDownFieldRingGroups3.css('display', 'none');
            self.dropDownFieldCopySubmenu3.css('display', 'none');
            self.dropDownFieldConference3.css('display', 'none');
            self.dropDownFieldAudiotext3.css('display', 'none');
            self.dropDownFieldVoicemail3.css('display', 'none');

            var value = $('#actions_3').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio3.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions3.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues3.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups3.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField3.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension3.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension3.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton3.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups3.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu3.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference3.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext3.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail3.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys4Load: function () {
            var self = this;

            self.dropDownAudio4 = $('#dropDownFieldAudio_4', this.element);
            self.dropDownExtension4 = $('#dropDownFieldExtension_4', this.element);
            self.textField4 = $('#textField_4', this.element);
            self.textFieldButton4 = $('#textFieldWithButton_4', this.element);
            self.dropDownVExtension4 = $('#dropDownFieldVoicemailExtension_4', this.element);
            self.dropDownUserGroups4 = $('#dropDownFieldUserGroups_4', this.element);
            self.dropDownFieldTransferExtentions4 = $('#dropDownFieldTransferExtentions_4', this.element);
            self.dropDownFieldQueues4 = $('#dropDownFieldQueues_4', this.element);
            self.dropDownFieldRingGroups4 = $('#dropDownFieldRingGroups_4', this.element);
            self.dropDownFieldCopySubmenu4 = $('#dropDownFieldCopySubmenu_4', this.element);
            self.dropDownFieldConference4 = $('#dropDownFieldConference_4', this.element);
            self.dropDownFieldAudiotext4 = $('#dropDownFieldAudiotext_4', this.element);
            self.dropDownFieldVoicemail4 = $('#dropDownFieldVoicemail_4', this.element);

            self.dropDownAudio4.css('display', 'none');
            self.dropDownExtension4.css('display', 'none');
            self.textField4.css('display', 'none');
            self.textFieldButton4.css('display', 'none');
            self.dropDownVExtension4.css('display', 'none');
            self.dropDownUserGroups4.css('display', 'none');
            self.dropDownFieldTransferExtentions4.css('display', 'none');
            self.dropDownFieldQueues4.css('display', 'none');
            self.dropDownFieldRingGroups4.css('display', 'none');
            self.dropDownFieldCopySubmenu4.css('display', 'none');
            self.dropDownFieldConference4.css('display', 'none');
            self.dropDownFieldAudiotext4.css('display', 'none');
            self.dropDownFieldVoicemail4.css('display', 'none');


            var value = $('#actions_4').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio4.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions4.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues4.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups4.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField4.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension4.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension4.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton4.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups4.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu4.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference4.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext4.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail4.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys5Load: function () {
            var self = this;

            self.dropDownAudio5 = $('#dropDownFieldAudio_5', this.element);
            self.dropDownExtension5 = $('#dropDownFieldExtension_5', this.element);
            self.textField5 = $('#textField_5', this.element);
            self.textFieldButton5 = $('#textFieldWithButton_5', this.element);
            self.dropDownVExtension5 = $('#dropDownFieldVoicemailExtension_5', this.element);
            self.dropDownUserGroups5 = $('#dropDownFieldUserGroups_5', this.element);
            self.dropDownFieldTransferExtentions5 = $('#dropDownFieldTransferExtentions_5', this.element);
            self.dropDownFieldQueues5 = $('#dropDownFieldQueues_5', this.element);
            self.dropDownFieldRingGroups5 = $('#dropDownFieldRingGroups_5', this.element);
            self.dropDownFieldCopySubmenu5 = $('#dropDownFieldCopySubmenu_5', this.element);
            self.dropDownFieldConference5 = $('#dropDownFieldConference_5', this.element);
            self.dropDownFieldAudiotext5 = $('#dropDownFieldAudiotext_5', this.element);
            self.dropDownFieldVoicemail5 = $('#dropDownFieldVoicemail_5', this.element);

            self.dropDownAudio5.css('display', 'none');
            self.dropDownExtension5.css('display', 'none');
            self.textField5.css('display', 'none');
            self.textFieldButton5.css('display', 'none');
            self.dropDownVExtension5.css('display', 'none');
            self.dropDownUserGroups5.css('display', 'none');
            self.dropDownFieldTransferExtentions5.css('display', 'none');
            self.dropDownFieldQueues5.css('display', 'none');
            self.dropDownFieldRingGroups5.css('display', 'none');
            self.dropDownFieldCopySubmenu5.css('display', 'none');
            self.dropDownFieldConference5.css('display', 'none');
            self.dropDownFieldAudiotext5.css('display', 'none');
            self.dropDownFieldVoicemail5.css('display', 'none');

            var value = $('#actions_5').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio5.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions5.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues5.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups5.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField5.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension5.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension5.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton5.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups5.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu5.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference5.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext5.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail5.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys6Load: function () {
            var self = this;

            self.dropDownAudio6 = $('#dropDownFieldAudio_6', this.element);
            self.dropDownExtension6 = $('#dropDownFieldExtension_6', this.element);
            self.textField6 = $('#textField_6', this.element);
            self.textFieldButton6 = $('#textFieldWithButton_6', this.element);
            self.dropDownVExtension6 = $('#dropDownFieldVoicemailExtension_6', this.element);
            self.dropDownUserGroups6 = $('#dropDownFieldUserGroups_6', this.element);
            self.dropDownFieldTransferExtentions6 = $('#dropDownFieldTransferExtentions_6', this.element);
            self.dropDownFieldQueues6 = $('#dropDownFieldQueues_6', this.element);
            self.dropDownFieldRingGroups6 = $('#dropDownFieldRingGroups_6', this.element);
            self.dropDownFieldCopySubmenu6 = $('#dropDownFieldCopySubmenu_6', this.element);
            self.dropDownFieldConference6 = $('#dropDownFieldConference_6', this.element);
            self.dropDownFieldAudiotext6 = $('#dropDownFieldAudiotext_6', this.element);
            self.dropDownFieldVoicemail6 = $('#dropDownFieldVoicemail_6', this.element);

            self.dropDownAudio6.css('display', 'none');
            self.dropDownExtension6.css('display', 'none');
            self.textField6.css('display', 'none');
            self.textFieldButton6.css('display', 'none');
            self.dropDownVExtension6.css('display', 'none');
            self.dropDownUserGroups6.css('display', 'none');
            self.dropDownFieldTransferExtentions6.css('display', 'none');
            self.dropDownFieldQueues6.css('display', 'none');
            self.dropDownFieldRingGroups6.css('display', 'none');
            self.dropDownFieldCopySubmenu6.css('display', 'none');
            self.dropDownFieldConference6.css('display', 'none');
            self.dropDownFieldAudiotext6.css('display', 'none');
            self.dropDownFieldVoicemail6.css('display', 'none');

            var value = $('#actions_6').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio6.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions6.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues6.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups6.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField6.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension6.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension6.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton6.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups6.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu6.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference6.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext6.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail6.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys7Load: function () {

            var self = this;

            self.dropDownAudio7 = $('#dropDownFieldAudio_7', this.element);
            self.dropDownExtension7 = $('#dropDownFieldExtension_7', this.element);
            self.textField7 = $('#textField_7', this.element);
            self.textFieldButton7 = $('#textFieldWithButton_7', this.element);
            self.dropDownVExtension7 = $('#dropDownFieldVoicemailExtension_7', this.element);
            self.dropDownUserGroups7 = $('#dropDownFieldUserGroups_7', this.element);
            self.dropDownFieldTransferExtentions7 = $('#dropDownFieldTransferExtentions_7', this.element);
            self.dropDownFieldQueues7 = $('#dropDownFieldQueues_7', this.element);
            self.dropDownFieldRingGroups7 = $('#dropDownFieldRingGroups_7', this.element);
            self.dropDownFieldCopySubmenu7 = $('#dropDownFieldCopySubmenu_7', this.element);
            self.dropDownFieldConference7 = $('#dropDownFieldConference_7', this.element);
            self.dropDownFieldAudiotext7 = $('#dropDownFieldAudiotext_7', this.element);
            self.dropDownFieldVoicemail7 = $('#dropDownFieldVoicemail_7', this.element);

            self.dropDownAudio7.css('display', 'none');
            self.dropDownExtension7.css('display', 'none');
            self.textField7.css('display', 'none');
            self.textFieldButton7.css('display', 'none');
            self.dropDownVExtension7.css('display', 'none');
            self.dropDownFieldTransferExtentions7.css('display', 'none');
            self.dropDownFieldQueues7.css('display', 'none');
            self.dropDownFieldRingGroups7.css('display', 'none');
            self.dropDownUserGroups7.css('display', 'none');
            self.dropDownFieldCopySubmenu7.css('display', 'none');
            self.dropDownFieldConference7.css('display', 'none');
            self.dropDownFieldAudiotext7.css('display', 'none');
            self.dropDownFieldVoicemail7.css('display', 'none');

            var value = $('#actions_7').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio7.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions7.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues7.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups7.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField7.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension7.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension7.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton7.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups7.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu7.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference7.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext7.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail7.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys8Load: function () {

            var self = this;

            self.dropDownAudio8 = $('#dropDownFieldAudio_8', this.element);
            self.dropDownExtension8 = $('#dropDownFieldExtension_8', this.element);
            self.textField8 = $('#textField_8', this.element);
            self.textFieldButton8 = $('#textFieldWithButton_8', this.element);
            self.dropDownVExtension8 = $('#dropDownFieldVoicemailExtension_8', this.element);
            self.dropDownUserGroups8 = $('#dropDownFieldUserGroups_8', this.element);
            self.dropDownFieldTransferExtentions8 = $('#dropDownFieldTransferExtentions_8', this.element);
            self.dropDownFieldQueues8 = $('#dropDownFieldQueues_8', this.element);
            self.dropDownFieldRingGroups8 = $('#dropDownFieldRingGroups_8', this.element);
            self.dropDownFieldCopySubmenu8 = $('#dropDownFieldCopySubmenu_8', this.element);
            self.dropDownFieldConference8 = $('#dropDownFieldConference_8', this.element);
            self.dropDownFieldAudiotext8 = $('#dropDownFieldAudiotext_8', this.element);
            self.dropDownFieldVoicemail8 = $('#dropDownFieldVoicemail_8', this.element);

            self.dropDownAudio8.css('display', 'none');
            self.dropDownExtension8.css('display', 'none');
            self.textField8.css('display', 'none');
            self.textFieldButton8.css('display', 'none');
            self.dropDownVExtension8.css('display', 'none');
            self.dropDownUserGroups8.css('display', 'none');
            self.dropDownFieldTransferExtentions8.css('display', 'none');
            self.dropDownFieldQueues8.css('display', 'none');
            self.dropDownFieldRingGroups8.css('display', 'none');
            self.dropDownFieldCopySubmenu8.css('display', 'none');
            self.dropDownFieldConference8.css('display', 'none');
            self.dropDownFieldAudiotext8.css('display', 'none');
            self.dropDownFieldVoicemail8.css('display', 'none');


            var value = $('#actions_8').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio8.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions8.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues8.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups8.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField8.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension8.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension8.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton8.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups8.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu8.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference8.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext8.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail8.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeys9Load: function () {
            var self = this;

            self.dropDownAudio9 = $('#dropDownFieldAudio_9', this.element);
            self.dropDownExtension9 = $('#dropDownFieldExtension_9', this.element);
            self.textField9 = $('#textField_9', this.element);
            self.textFieldButton9 = $('#textFieldWithButton_9', this.element);
            self.dropDownVExtension9 = $('#dropDownFieldVoicemailExtension_9', this.element);
            self.dropDownFieldTransferExtentions9 = $('#dropDownFieldTransferExtentions_9', this.element);
            self.dropDownFieldQueues9 = $('#dropDownFieldQueues_9', this.element);
            self.dropDownFieldRingGroups9 = $('#dropDownFieldRingGroups_9', this.element);
            self.dropDownUserGroups9 = $('#dropDownFieldUserGroups_9', this.element);
            self.dropDownFieldCopySubmenu9 = $('#dropDownFieldCopySubmenu_9', this.element);
            self.dropDownFieldConference9 = $('#dropDownFieldConference_9', this.element);
            self.dropDownFieldAudiotext9 = $('#dropDownFieldAudiotext_9', this.element);
            self.dropDownFieldVoicemail9 = $('#dropDownFieldVoicemail_9', this.element);

            self.dropDownAudio9.css('display', 'none');
            self.dropDownExtension9.css('display', 'none');
            self.textField9.css('display', 'none');
            self.textFieldButton9.css('display', 'none');
            self.dropDownVExtension9.css('display', 'none');
            self.dropDownUserGroups9.css('display', 'none');
            self.dropDownFieldTransferExtentions9.css('display', 'none');
            self.dropDownFieldQueues9.css('display', 'none');
            self.dropDownFieldRingGroups9.css('display', 'none');
            self.dropDownFieldCopySubmenu9.css('display', 'none');
            self.dropDownFieldConference9.css('display', 'none');
            self.dropDownFieldAudiotext9.css('display', 'none');
            self.dropDownFieldVoicemail9.css('display', 'none');

            var value = $('#actions_9').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudio9.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentions9.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueues9.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroups9.css('display', 'block');
                    break;
                case 'External Number':
                    self.textField9.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtension9.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtension9.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButton9.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroups9.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenu9.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConference9.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotext9.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemail9.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }

        },
        checkKeysStarLoad: function () {
            var self = this;

            self.dropDownAudioStar = $('#dropDownFieldAudio_star', this.element);
            self.dropDownExtensionStar = $('#dropDownFieldExtension_star', this.element);
            self.textFieldStar = $('#textField_star', this.element);
            self.textFieldButtonStar = $('#textFieldWithButton_star', this.element);
            self.dropDownVExtensionStar = $('#dropDownFieldVoicemailExtension_star', this.element);
            self.dropDownFieldTransferExtentionsStar = $('#dropDownFieldTransferExtentions_star', this.element);
            self.dropDownFieldQueuesStar = $('#dropDownFieldQueues_star', this.element);
            self.dropDownFieldRingGroupsStar = $('#dropDownFieldRingGroups_star', this.element);
            self.dropDownUserGroupsStar = $('#dropDownFieldUserGroups_star', this.element);
            self.dropDownFieldCopySubmenuStar = $('#dropDownFieldCopySubmenu_star', this.element);
            self.dropDownFieldConferenceStar = $('#dropDownFieldConference_star', this.element);
            self.dropDownFieldAudiotextStar = $('#dropDownFieldAudiotext_star', this.element);
            self.dropDownFieldVoicemailStar = $('#dropDownFieldVoicemail_star', this.element);

            self.dropDownAudioStar.css('display', 'none');
            self.dropDownExtensionStar.css('display', 'none');
            self.textFieldStar.css('display', 'none');
            self.textFieldButtonStar.css('display', 'none');
            self.dropDownVExtensionStar.css('display', 'none');
            self.dropDownUserGroupsStar.css('display', 'none');
            self.dropDownFieldTransferExtentionsStar.css('display', 'none');
            self.dropDownFieldQueuesStar.css('display', 'none');
            self.dropDownFieldRingGroupsStar.css('display', 'none');
            self.dropDownFieldCopySubmenuStar.css('display', 'none');
            self.dropDownFieldConferenceStar.css('display', 'none');
            self.dropDownFieldAudiotextStar.css('display', 'none');
            self.dropDownFieldVoicemailStar.css('display', 'none');

            var value = $('#actions_star').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudioStar.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentionsStar.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueuesStar.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroupsStar.css('display', 'block');
                    break;
                case 'External Number':
                    self.textFieldStar.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtensionStar.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtensionStar.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButtonStar.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroupsStar.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenuStar.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConferenceStar.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotextStar.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemailStar.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }
        },
        checkKeysHashLoad: function () {
            var self = this;

            self.dropDownAudioHash = $('#dropDownFieldAudio_hash', this.element);
            self.dropDownExtensionHash = $('#dropDownFieldExtension_hash', this.element);
            self.textFieldHash = $('#textField_hash', this.element);
            self.textFieldButtonHash = $('#textFieldWithButton_hash', this.element);
            self.dropDownVExtensionHash = $('#dropDownFieldVoicemailExtension_hash', this.element);
            self.dropDownUserGroupsHash = $('#dropDownFieldUserGroups_hash', this.element);
            self.dropDownFieldTransferExtentionsHash = $('#dropDownFieldTransferExtentions_hash', this.element);
            self.dropDownFieldQueuesHash = $('#dropDownFieldQueues_hash', this.element);
            self.dropDownFieldRingGroupsHash = $('#dropDownFieldRingGroups_hash', this.element);
            self.dropDownFieldCopySubmenuHash = $('#dropDownFieldCopySubmenu_hash', this.element);
            self.dropDownFieldConferenceHash = $('#dropDownFieldConference_hash', this.element);
            self.dropDownFieldAudiotextHash = $('#dropDownFieldAudiotext_hash', this.element);
            self.dropDownFieldVoicemailHash = $('#dropDownFieldVoicemail_hash', this.element);

            self.dropDownAudioHash.css('display', 'none');
            self.dropDownExtensionHash.css('display', 'none');
            self.textFieldHash.css('display', 'none');
            self.textFieldButtonHash.css('display', 'none');
            self.dropDownVExtensionHash.css('display', 'none');
            self.dropDownUserGroupsHash.css('display', 'none');
            self.dropDownFieldTransferExtentionsHash.css('display', 'none');
            self.dropDownFieldQueuesHash.css('display', 'none');
            self.dropDownFieldRingGroupsHash.css('display', 'none');
            self.dropDownFieldCopySubmenuHash.css('display', 'none');
            self.dropDownFieldConferenceHash.css('display', 'none');
            self.dropDownFieldAudiotextHash.css('display', 'none');
            self.dropDownFieldVoicemailHash.css('display', 'none');

            var value = $('#actions_hash').find(":selected").val();

            switch (value) {
                case 'Playfile':
                    self.dropDownAudioHash.css('display', 'block');
                    break;
                case 'Transfer to extension':
                    self.dropDownFieldTransferExtentionsHash.css('display', 'block');
                    break;
                case 'Queues':
                    self.dropDownFieldQueuesHash.css('display', 'block');
                    break;
                case 'Ring Groups':
                    self.dropDownFieldRingGroupsHash.css('display', 'block');
                    break;
                case 'External Number':
                    self.textFieldHash.css('display', 'block');
                    break;
                case 'Deposit to user personal voicemail box':
                    self.dropDownExtensionHash.css('display', 'block');
                    break;
                case 'Deposit to Common Voicemail box':
                    self.dropDownVExtensionHash.css('display', 'block');
                    break;

                case 'Sub Menu':
                    self.textFieldButtonHash.css('display', 'block');
                    break;

                case 'Dial by name within User Group':
                    self.dropDownUserGroupsHash.css('display', 'block');
                    break;

                case 'Copy Sub Menu':
                    self.dropDownFieldCopySubmenuHash.css('display', 'block');
                    break;


                case 'Conference':
                    self.dropDownFieldConferenceHash.css('display', 'block');
                    break;
                case 'IVR':
                    self.dropDownFieldAudiotextHash.css('display', 'block');
                    break;
                case 'Voicemail':
                    self.dropDownFieldVoicemailHash.css('display', 'block');
                    break;
                case 'Return to Previous Menu':
                case 'Dial by name':
                case 'No Action':
                case 'Repeat Menu':
                case 'Disconnect':
                case 'Login to voicemail box':
                default :
                    break;
            }

        }

    });
}));

$(document).ready(function () {
    $("#auto-attendant-master-setting-form").dynamicKeys();

    $('[data-plugin="sortable"]').each(function () {
        var $this = $(this),
            $options = $.extend({}, $this.data());
        Sortable.create(this, $options);
    });
});

