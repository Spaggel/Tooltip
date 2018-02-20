define([
    'jquery',
    'Spaggel_Tooltip/js/tooltipster.bundle.min'
],function($){
    return function (config, element) {
        $(document).ready(function () {
            $(element).tooltipster(config)
        });
    }
});