(function (HawkSearch) {
    var searchEndpoint = "https://essearchapi-na.hawksearch.com";
    var trackingEndpoint = "https://tracking-na.hawksearch.com";

    HawkSearch.config = {
        clientId: "3c6dd3cf4b824a869ec3cdb3cf1dbf47",
        css: {
            defaultStyles: false,
            customStyles: '/css/fw-hawksearch-handlebars.css'
        },
        placeholderImageUrl: "https://fwwebbimage.fwwebb.com/Product/Gallery/noimage.jpg",
        fieldMappings: {
            title: "result_display_title",
            url: "result_display_uri"
        },
        autocomplete: {
            endpointUrl: searchEndpoint
        },
        search: {
            endpointUrl: searchEndpoint,
            itemTypes: {
                default: 'product'
            },
            url: "/wosn/"
        },
        tracking: {
            endpointUrl: trackingEndpoint
        },
        shadowDom: false,
        components: {
            autocomplete: {
                template: 'hs-autocomplete-template',
            },
            "search-field": {
                template: 'hs-search-field-template',
            },
            "search-results": {
                template: 'hs-search-results-template',
            },
            "pagination": {
                template: 'hs-search-results-pagination',
            },
            "sorting": {
                template: 'hs-search-results-sorting',
            },
            "search-results-list": {
                template: 'hs-search-results-list-template',
            },
            "search-results-item": {
                template: 'hs-search-results-item-template',
            },
            "facets-list": {
                strings: {
                    heading: "Refine Search"
                },
                template: 'hs-facets-list-template'
            },
            "selected-facets": {
                template: 'hs-selected-facets-template'
            },
            "search-within-facet": {
                template: 'hs-search-within-facet-template'
            }
        }
    };
}(window.HawkSearch = window.HawkSearch || {}));

window.hsTiming = {};

async function hsUpdateAttributes(searchResponse) {
    var postData = new URLSearchParams(),
        resultsJSON = searchResponse.results || [];

    Object.keys(window.hsTiming.Output).forEach((key) => {
        postData.append(key, window.hsTiming.Output[key]);
    });

    if (resultsJSON && resultsJSON.length > 0) {
        resultsJSON.forEach((item, index) => {
            postData.append('SEARCH.RESULTS', item.id + "*1");
        });
        try {
            await fetch('/wobf/hsearch.index.itemupdate', {
                method: 'POST',
                body: postData.toString()
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                resultsJSON.forEach((item, index) => {
                    HawkSearch.searchResponse.results[index].wkAttributes = { ... {}, ...data[item.id] };
                });
            });
        } catch (error) {
            console.log(error);
            resultsJSON.forEach((item, index) => {
                HawkSearch.searchResponse.results[index].wkAttributes = {};
            });
        }
        //Manually call render function on each item to update items in place
        //Calling the search results item list render function deletes and re-adds all items, resulting in UI blink
        document.querySelectorAll('hawksearch-search-results-item').forEach(item => {
            item.render();
        });
    }
};

function hsScrollToTop() {
    if (window.jQuery) {
        jQuery("html, body").animate({ scrollTop: 0 }, 500);
    }
};

/**
    Event Tracking Listener
*/
function searchItemTrackATC(event, id, price) {
    var formValues = new FormData(event.target);
    var qty = formValues.get('QTY<-2>');
    HawkSearch.services.tracking.trackAddToCart(id, qty, price, 'USD');
}

//Temporary Debugging: print all hawksearch events to console
var hsEventList = [
    'hawksearch:initialized',
    'hawksearch:before-autocomplete-executed',
    'hawksearch:after-autocomplete-executed',
    'hawksearch:autocomplete-completed',
    'hawksearch:before-search-executed',
    'hawksearch:after-search-executed',
    'hawksearch:search-completed',
    'hawksearch:before-recommendations-executed',
    'hawksearch:after-recommendations-executed',
    'hawksearch:recommendations-completed'
];
hsEventList.forEach((eventName) => {
    addEventListener(eventName, function (event) {
        console.log(event.type);
        console.log(eventName)
        console.log(event.detail);
    });
});

window.addEventListener('hawksearch:initialized', (event) => {
    /**
        Utility Helpers - General-use helper functions
    */
    console.log("David Test in handlebars.js")
    HawkSearch.handlebars.registerHelper("isNotEmpty", function (attribute) {
        return typeof attribute !== 'undefined' && !HawkSearch.handlebars.Utils.isEmpty(attribute) & !(HawkSearch.handlebars.Utils.isArray(attribute) && attribute[0] === '');
    });

    HawkSearch.handlebars.registerHelper("wkAttributesHideLoad", function () {
        if (typeof this.wkAttributes === 'undefined') {
            return 'hawksearch-placeholder';
        } else {
            return '';
        }
    });

    HawkSearch.handlebars.registerHelper("wkAttributesLoading", function () {
        return typeof this.wkAttributes === 'undefined';
    });

    /**
        Debugging - Temporary
    */
    HawkSearch.handlebars.registerHelper('json', function (context) {
        return JSON.stringify(context);
    });

    /**
        Autocomplete Helpers - Used to reformat product data for autocomplete display
    */
    HawkSearch.handlebars.registerHelper("autocompleteItem_title", function () {
        //This should eventually be moved to index-time generation, like result_display_title
        var mfgName = this.attributes['manufacturer_name'] || '',
            mfgPartNum = this.attributes['manufacturer_part_number'] || '',
            title = this.attributes['result_display_title'] || '';
        return (`${mfgName} ${mfgPartNum} ${title}`).replace(/\s+/g, ' ').trim();
    });
    HawkSearch.handlebars.registerHelper("autocompleteItem_productUrl", function () {
        return this.attributes["result_display_uri"] || this.attributes["url_detail"] || this.url;
    });
    HawkSearch.handlebars.registerHelper("autocompleteItem_imageUrl", function () {
        return this.imageUrl && this.imageUrl.replace("Gallery", "Thumbnail");
    });

    /**
        Search Result Helpers - Used in the display of results on SERPs and LPs
    */
    HawkSearch.handlebars.registerHelper("searchItem_isLoggedIn", function () {
        return !!parseInt(window.hsCustomIsLoggedIn);
    });
    HawkSearch.handlebars.registerHelper("searchItem_isWOGUEST", function () {
        return this.attributes.hasOwnProperty('visitor_target') && this.attributes['visitor_target'].includes("WOGUEST");
    });
    HawkSearch.handlebars.registerHelper("searchItem_userPrice", function () {
        return (this.wkAttributes && this.wkAttributes['price']) || this.price / 100;
    });
    HawkSearch.handlebars.registerHelper("searchItem_idBrOne", function () {
        return (this.id && `${this.id}*1`) || '';
    });
    HawkSearch.handlebars.registerHelper("paginationSummary", function () {
        var paginationSummaryElements = document.querySelectorAll('[name="hsPaginationSummary"]'),
            summary = this.strings ? this.strings.summary : '';
        paginationSummaryElements.forEach(function (element) {
            element.innerText = summary;
        });
    });

    HawkSearch.handlebars.registerHelper("groupedSelectedFacets", function () {
        var groupedSelections = [],
            groupedKeys = [];
        this.selections.forEach((e) => {
            var groupInd = groupedKeys.indexOf(e.field);
            if (groupInd >= 0) {
                groupedSelections[groupInd].selections.push(e);
            } else {
                groupedSelections.push({
                    field: e.field,
                    title: e.title,
                    selections: [e]
                });
                groupedKeys.push(e.field);
            }
        });
        return groupedSelections;
    });
});

window.addEventListener('hawksearch:after-sorting-rendered', (event) => {
    var sortElement = event.detail.component;

    sortElement.addEventListener('change', hsScrollToTop);
});

window.addEventListener('hawksearch:after-pagination-rendered', (event) => {
    var pageElement = event.detail.component;

    pageElement.addEventListener('click', (clickEvent) => {
        if (clickEvent.target.hasAttribute('hawksearch-page')) {
            hsScrollToTop();
        }
    });
});

window.addEventListener('hawksearch:before-search-executed', (event) => {
    window.hsTiming.Before = Date.now();
});

window.addEventListener('hawksearch:after-search-executed', (event) => {
    const searchResponse = event.detail;
    window.hsTiming.After = Date.now();

    try {
        var resultCount = searchResponse.Pagination.NofResults;
        if (resultCount === 1) {
            window.location.assign(searchResponse.Results[0].Document.result_display_uri[0]);
        } else if (resultCount === 0) {
            window.location.assign("/wobf/hsearch.index.nothing+SEARCHTERMS=" + (searchResponse.Keyword).replaceAll(' ', '+'));
        }
    } catch (error) {
        if (error instanceof TypeError) {
            console.log('Warning: Unable to read nested properties of searchResponse')
        } else {
            throw error;
        }
    }
});

window.addEventListener('hawksearch:search-completed', (event) => {
    const searchResponse = event.detail;
    window.hsTiming.Completed = Date.now();
    window.hsTiming.Output = {
        "HAWK.TIME": (window.hsTiming.After - window.hsTiming.Before) / 1000,
        "HAWK.TIMES2": (window.hsTiming.Completed - window.hsTiming.After) / 1000,
        "HAWK.TIME.TOTAL": (window.hsTiming.Completed - window.hsTiming.Before) / 1000,
        "TIME3": (window.hsTiming.Before - (new Date()).setHours(0, 0, 0, 0)) / 1000,
        "RECCOUNT": searchResponse.pagination.totalResults
    };

    hsUpdateAttributes(searchResponse);
});

window.addEventListener('hawksearch:autocomplete-completed', (event) => {
    const acResponse = event.detail;
    try {
        var categoryCount = acResponse.categories.results.length;
        if (categoryCount > 0) {
            for (var i = 0; i < categoryCount; i++) {
                acResponse.categories.results[i].value = acResponse.categories.results[i].value.replaceAll("%20", " ");
            }
        }
    } catch (error) {
        if (error instanceof TypeError) {
            console.log('Warning: Unable to read nested properties of acResponse');
        } else {
            throw error;
        }
    }
});