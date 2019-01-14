/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

/**
 * Defining global scope object: pnp
 */
(function( pnp, $, undefined ) {

    /**
     * @name init
     * @access private
     * @description Private initiation method executed on DOM load
     */
    function init () {

        if (!Array.prototype.pushDistinct) {
            Array.prototype.pushDistinct = function(element) {
                if (this.indexOf(element) === -1) {
                    this.push(element);
                }
            };
        }

        if (!String.prototype.endsWith) {
            String.prototype.endsWith = function(search, this_len) {
                if (this_len === undefined || this_len > this.length) {
                    this_len = this.length;
                }
                return this.substring(this_len - search.length, this_len) === search;
            };
        }

        if (!String.prototype.incrementTrailingNumber) {
            String.prototype.incrementTrailingNumber = function() {
                var trailingNumIdx = this.search(/[0-9]/);
                var value = this.substring(trailingNumIdx, this.length);
                var headingStr = this.substr(0, trailingNumIdx);
                return headingStr + (parseInt(value) + 1);
            };
        }

        if (!String.prototype.removeTrailingNumber) {
            String.prototype.removeTrailingNumber = function() {
                var trailingNumIdx = this.search(/[0-9]/);
                var value = this.substring(trailingNumIdx, this.length);
                var headingStr = this.substr(0, trailingNumIdx);
                return headingStr + (parseInt(value) + 1);
            };
        }
    }

    /*
     * Execute initialization method for pnp.js
     */
    init();

}( window.pnp = window.pnp || {}, jQuery ));

/**
 *
 */
(function( pnp, $, undefined ) {

    pnp.character = {

        // Define public variables
        charObj : {},
        pageURLPram : "",
        keyUpEvent : null,

        /**
         * Register and un-register events
         * @access public
         */
        init : function () {

            pnp.character.pageURLPram = pnp.util.urlQuery("p", false).toLowerCase();

            $(document).on("submit", "form.api", function (e) {
                e.preventDefault();
            });

            $(document).on("click", "form.api button#cancel", function () {
                var p = (pnp.character.pageURLPram) ? "?p=" + pnp.character.pageURLPram : "";
                window.location.href = "/pages/characters.php" + p;
            });

            $(document).on("click", "form.api button.operation", function (e) {
                pnp.character.keyUpEvent = null;
                createOrUpdateCharacter(e.target);
            });

            $(document).on("click", "form fieldset div.input-group button", function (e) {
                pnp.ui.appendNewInputGroup(e.target);
            });

            loadCharacterData();
        },

        registerInputListener : function () {
            var inputs = $("form.api button.operation#update").closest("form.api").find("input");
            $(inputs).off();
            $(inputs).on("keyup", function (e) {
                pnp.character.keyUpEvent = e;
                setTimeout(function () {
                    if (pnp.character.keyUpEvent === e) {
                        var button = $(e.target).closest("form.api").find("button.operation#update");
                        //createOrUpdateCharacter(button);
                    }
                }, 10000);
            });
        }
    };

    function loadCharacterData () {
        pnp.character.registerInputListener();
        if ($("form.api").length && $("form.api button#update").length) {
            var what = (pnp.character.pageURLPram) ? "?what=" + pnp.character.pageURLPram : "?what=*";
            $.get("api/character.php" + what, function (data) {
                fillFormWithCharacterData($("form.api"), data);
            })
                .fail(function (data) {
                    pnp.ui.addAlertToMain(data.responseText, "Error loading data", "danger");
                });
        }
    }

    /**
     * @param {jQuery} form
     * @param {JSON} data
     */
    function fillFormWithCharacterData (form, data) {
        $.each(data, function (key1, value1) {
            var cFieldset = $(form).find("fieldset#"+key1);
            if (cFieldset.length) {
                $.each(value1, function (key2, value2) {
                    if (Array.isArray(value2)) {
                        $.each(value2, function (key3, value3) {
                            var trailingNum = parseInt(key3) + 1;
                            var input = $(cFieldset).find("input[aria-describedby='" + key2 + trailingNum + "']");
                            if (input.length) {
                                input.val(value3);
                                if (!(trailingNum === value2.length && value3 === "")) {
                                    pnp.ui.appendNewInputGroup($(input).next("div").children("button"));
                                }
                            } else {
                                var inputs = $(cFieldset).find("input[aria-describedby='" + key2 + "']");
                                $(inputs[key3]).val(value3);
                            }
                        });
                    } else {
                        $(cFieldset).find("input[aria-describedby='" + key2 + "']").val(value2);
                    }
                });
            } else {
                return true;
            }
        });
    }

    /**
     * @param {EventTarget} target
     */
    function createOrUpdateCharacter (target) {
        var buttonJ = $(target);
        var operation = buttonJ.attr("id");
        buildCharacterJSONFromForm(buttonJ.closest("form.api"), function () {
            $.post("api/character.php", { chardata: JSON.stringify(pnp.character.charObj), operation: operation })
                .done(function (){
                    if (operation === "create") {
                        window.location.href = "/pages/characters.php";
                    } else if (operation === "update") {
                        pnp.ui.addAlertToMain("Your character data has been updated successfully.", "Saved", "info");
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                });
        });
    }

    /**
     * Callback for building the global charObj variable from the given form.
     *
     * @callback buildCharacterJSONFromFormCallback
     */

    /**
     * @param {jQuery} form
     * @param {buildCharacterJSONFromFormCallback} callback - A callback to run.
     */
    function buildCharacterJSONFromForm (form, callback) {
        pnp.character.charObj = {};
        var fieldsetsJ = $(form).find("fieldset");

        fieldsetsJ.each(function() {
            var fieldset = $(this).attr("id");
            pnp.character.charObj[fieldset] = {};
            var spansJ = $(this).find("span");

            spansJ.each(function (){
                var inputsJ = $(this).parent("div").nextUntil("div");
                var key = $(this).attr("id");
                if (inputsJ.length > 1) {
                    inputsJ.each(function (i) {
                        pnp.util.addValueToCharObject(fieldset, key, $(this).val(), i);
                    });
                } else {
                    var value = $(inputsJ).val();
                    if (key[key.length-1].match(/[0-9]/)) {
                        var trailingNumIdx = key.search(/[0-9]/);
                        var idx = parseInt(key.substring(trailingNumIdx, key.length));
                        var headingStr = key.substr(0, trailingNumIdx);
                        pnp.util.addValueToCharObject(fieldset, headingStr, value, idx);
                    } else {
                        pnp.util.addValueToCharObject(fieldset, key, value);
                    }
                }
            });
        });
        callback();
    }

}( window.pnp = window.pnp || {}, jQuery ));

(function( pnp, $, undefined ) {

    pnp.ui = {

        // Define public variables

        /**
         * @param {EventTarget} button
         * @access public
         */
        appendNewInputGroup : function (button) {
            var srcDIG = $(button).closest("div.input-group");
            var input = $(srcDIG).find("input");
            if (input.val().trim() === "") {
                input.addClass("is-invalid");
                input.val("");
            } else {
                input.removeClass("is-invalid");
                var copy = srcDIG.clone();
                $(copy).find("input").val("");
                var newspan = $(copy).find("span");
                var newinput = $(copy).find("input");
                var incText = $(newspan).text().incrementTrailingNumber();
                var incID = $(newspan).attr("id").incrementTrailingNumber();
                $(newspan).attr("id", incID).text(incText);
                $(newinput).attr("aria-describedby", incID).attr("aria-label", incText);
                $(srcDIG).find("div.input-group-append").remove();
                $(srcDIG).find("span").addClass("border-bottom-0");
                $(input).addClass("border-bottom-0");
                $(srcDIG).parent().append(copy);
                pnp.character.registerInputListener();
                $("form.api button.operation").click();
            }
        },

        /**
         * @name addAlertToMain
         * @description Displays an alert to the main container of the page.
         * @param {string} message
         * @param {string} title
         * @param {string} type
         */
        addAlertToMain : function (message, title, type) {
            if (message) {
                var ttl = (title) ? "<strong>"+title+"!</strong> " : "";
                var tp = (type) ? type : "info";
                var alert =     $("<div class=\"alert alert-" + tp + " alert-dismissible fade show\" role=\"alert\">\n" +
                    "                " + ttl + message + "\n" +
                    "                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                    "                    <span aria-hidden=\"true\">&times;</span>\n" +
                    "                </button>\n" +
                    "            </div>");

                $(alert).fadeTo(5000, 500).slideUp(500, function () {
                    $(alert).slideUp(500);
                });

                $(alert).prependTo($("main"));
            }
        }
    };

}( window.pnp = window.pnp || {}, jQuery ));

/*
 * Utility and Helper functions
 */
(function( pnp, $, undefined ) {

    pnp.util = {

        /**
         * @param {string} fieldset
         * @param {string} key
         * @param {Object} value
         * @param {int} idx
         */
        addValueToCharObject : function (fieldset, key, value, idx) {
            fieldset = fieldset.toLowerCase();
            key = key.toLowerCase();
            if (typeof idx !== "undefined") {
                if (!pnp.character.charObj[fieldset].hasOwnProperty(key)) {
                    pnp.character.charObj[fieldset][key] = [];
                }
                pnp.character.charObj[fieldset][key].splice(idx, 0, $.trim(value.toString()));
            } else {
                pnp.character.charObj[fieldset][key] = $.trim(value.toString());
            }
        },

        /**
         * @name urlQuery
         * @description Adds a parameter to the chart URL
         * @param {string} query
         * @param {boolean} upperQuery
         */
        urlQuery : function (query, upperQuery) {
            query = query.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var upper = (upperQuery === null) ? true : upperQuery;
            var expr = "[\\?&]" + query + "=([^&#]*)";
            var regex = new RegExp(expr);
            var results = regex.exec(decodeURI(window.location.href));
            if (results !== null) {
                if (upper) {
                    return decodeURIComponent(results[1]).toUpperCase();
                } else {
                    return decodeURIComponent(results[1]);
                }
            } else {
                return "";
            }
        }

    };

}( window.pnp = window.pnp || {}, jQuery ));