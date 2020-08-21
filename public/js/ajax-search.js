
function registerSearchTable(formSelector, tableSelector, callback)
{
    $(document).on("keypress", formSelector, function (event) {
        return event.keyCode !== 13;
    });

    $(document).on('input', formSelector, function () {
        loadSearchTable(formSelector, tableSelector, 1, callback);
    });

    // Select 2 box change event
    $(document).on("change", ".select2-single", function () {
        loadSearchTable(formSelector, tableSelector, 1, callback);
    });
    
    // Radio button change event
    $(formSelector + 'input[type=radio]').on("change", function () {
        loadSearchTable(formSelector, tableSelector, 1, callback);
    });

    $(document).on('click', '.paginate_button ', function (e) {
        e.preventDefault();
        var page = $(this).children().attr('data-dt-idx');
        loadSearchTable(formSelector, tableSelector, page, callback);
    });
}

function loadSearchTable(formSelector, tableSelector, page, callback)
{
    window.page = 1; // reset page count 
    
    var url = $(formSelector).attr('action');
    console.log('url:', url);
    var data = $(formSelector).serialize() + "&action=" + "search" + "&page=" + page;
    
    window.currentRequest = $.ajax({
        url: url,
        data: data,
        dataType: "HTML",
        beforeSend: function () {
            if (window.currentRequest != null) {
                window.currentRequest.abort();
            }
            // start loader 
            $(tableSelector).loading({
                message: input_loader
            });
        },
        success: function (html) {
            
            $(tableSelector).loading('stop');
            try {
                var response = $.parseJSON(html);
                
                if($.trim(response.status) === 'error') {
                    alertify.error(response.message);
                    return false;
                }
            } catch(e) {
                //JSON parse error, this is not json (or JSON isn't in your browser)
                $(tableSelector).html(html);
                callback();
            }
        },
        error: function () {
            // SHOW MESSAGE
            $(tableSelector).loading('stop');
        },
        complete: function () {
            // Remove spinning icon
            $(tableSelector).loading('stop');
        }        
    });
}

$(document).ready(function () {
    registerSearchTable("#recordSearch", '#listRecords', function () {
    });
});

$(document).ready(function ()
{
    $(document).on('click', '.pagination a', function (event)
    {
        $('.pagination li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var url = $(this).attr('href');
        var page = $(this).attr('href').split('page=')[1];
        getData(url, page);
    });

    // Sorting table columns on click   
    $(document).on('click', '#listingTable th a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getSortTableData(url);
    });

});

// Get sort table data
function getSortTableData(url) {
    var tableSelector = "#listingTable";
    $.ajax({
        url: url,
        beforeSend: function () {
            // start loader 
            $(tableSelector).loading('stop');
            $(tableSelector).loading({
                message: input_loader
            });
        },
    }).done(function (data) {
        $(tableSelector).loading('stop');
        $("#listRecords").empty().html(data);
    });
}

function getData(url, page) {

    var tableSelector = "#listingTable";
    var formSelector = "#recordSearch";

    // var url = window.location.href + '?page=' + page;
    var url = url;
    var data = $(formSelector).serialize() + "&action=" + "search" + "&page=" + page;
    $.ajax(
            {
                url: url,
                data: data,
                datatype: "HTML",
                beforeSend: function () {
                    // start loader 
                    $(tableSelector).loading({
                        message: input_loader_full
                    });
                },
            })
            .done(function (data)
            {
                //console.log('data:', data)
                $(tableSelector).loading('stop');

                $("#listRecords").empty().html(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError)
            {
                $(tableSelector).loading('stop');
                alertify.error('OOPs!! Something went wrong, Please try again.');
            });
}
