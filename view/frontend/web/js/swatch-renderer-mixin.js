define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, {
            /**
             * Render controls
             *
             * @private
             */
            _RenderControls: function () {
                var $widget = this,
                    container = this.element,
                    classes = this.options.classes,
                    chooseText = this.options.jsonConfig.chooseText;

                $widget.optionsMap = {};

                $.each(this.options.jsonConfig.attributes, function () {
                    var item = this,
                        controlLabelId = 'option-label-' + item.code + '-' + item.id,
                        options = $widget._RenderSwatchOptions(item, controlLabelId),
                        select = $widget._RenderSwatchSelect(item, chooseText),
                        input = $widget._RenderFormInput(item),
                        listLabel = '',
                        label = '';

                    // Show only swatch controls
                    if ($widget.options.onlySwatches && !$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                        return;
                    }

                    if ($widget.options.enableControlLabel) {
                        label +=
                            '<span id="' + controlLabelId + '" class="' + classes.attributeLabelClass + '">' +
                            $('<i></i>').text(item.label).html() +
                            '</span>' +
                            '<span class="' + classes.attributeSelectedOptionLabelClass + '"></span>';
                    }

                    if ($widget.inProductList) {
                        $widget.productForm.append(input);
                        input = '';
                        listLabel = 'aria-label="' + $('<i></i>').text(item.label).html() + '"';
                    } else {
                        listLabel = 'aria-labelledby="' + controlLabelId + '"';
                    }

                    // Check if the tooltip has content and we are not in the product list,
                    // otherwise set it to an empty string
                    var hasTooltip = item.tooltip.length > 0 && !$widget.inProductList;
                    var toolTipHtml = hasTooltip ?
                        '<span class="spaggel-tooltip">' +
                        '<a href="#" class="tooltip-toggle">?</a>' +
                        '<span class="tooltip-content">' + item.tooltip + '</span>' +
                        '</span>'
                        : "";

                    // Create new control
                    container.append(
                        '<div class="' + classes.attributeClass + ' ' + item.code + '" ' +
                        'attribute-code="' + item.code + '" ' +
                        'attribute-id="' + item.id + '">' +
                        label +
                        toolTipHtml + // append the tooltip (possibly empty if string is empty in backend)
                        '<div aria-activedescendant="" ' +
                        'tabindex="0" ' +
                        'aria-invalid="false" ' +
                        'aria-required="true" ' +
                        'role="listbox" ' + listLabel +
                        'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                        options + select +
                        '</div>' + input +
                        '</div>'
                    );

                    $widget.optionsMap[item.id] = {};

                    // Aggregate options array to hash (key => value)
                    $.each(item.options, function () {
                        if (this.products.length > 0) {
                            $widget.optionsMap[item.id][this.id] = {
                                price: parseInt(
                                    $widget.options.jsonConfig.optionPrices[this.products[0]].finalPrice.amount,
                                    10
                                ),
                                products: this.products
                            };
                        }
                    });
                });

                // Connect Tooltip
                container
                    .find('[option-type="1"], [option-type="2"], [option-type="0"], [option-type="3"]')
                    .SwatchRendererTooltip();

                // Hide all elements below more button
                $('.' + classes.moreButton).nextAll().hide();

                // Handle events like click or change
                $widget._EventListener();

                // Rewind options
                $widget._Rewind(container);

                //Emulate click on all swatches from Request
                $widget._EmulateSelected($.parseQuery());
                $widget._EmulateSelected($widget._getSelectedAttributes());
            }
        });

        return $.mage.SwatchRenderer;
    };
});
