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

    }

    /*
     * Execute initialization method for pnp.js
     */
    init();

}( window.pnp = window.pnp || {}, jQuery ));

/*
 * Utility and Helper functions
 */
(function( pnp, $, undefined ) {

    pnp.util = {

        /**
         * @name urlQuery
         * @description Adds a parameter to the chart URL
         * @param query
         * @param upperQuery
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

    function getInitialSizeObj (size) {

    }
}( window.pnp = window.pnp || {}, jQuery ));

/**
 *
 */
(function( pnp, $, undefined ) {

    pnp.character = {

        // Define public variables

        // must public
        init : function () {

            $("form.api").submit(function(e) {
                e.preventDefault();
            });

            $(document).on("click", "form#api button#cancel", function () {
                window.location.href = "/pages/characters.php";
            });

            $(document).on("click", "form#api button#create", function (e) {
                createOrUpdateCharacter(e);
            });
        }
    };

    function createOrUpdateCharacter (event) {
        var type =
        $.post( "api/character.php", {} )
            .done(function( data ) {
                alert( "Data Loaded: " + data );
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                alert(errorThrown);
            });
    }

    function buildCharacterJSONFromForm (form) {

    }

}( window.pnp = window.pnp || {}, jQuery ));