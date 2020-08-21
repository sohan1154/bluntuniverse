
function loadMore(page)
{
    console.log('load more is called')
    let url = window.location.href;

    var data = "action=" + "loadMore" + "&page=" + page;

    console.log('url:', url);
    console.log('data:', data);
    
    window.currentRequest = $.ajax({
        url: url,
        data: data,
        // dataType: "json",
        beforeSend: function () {
            console.log('beforeSend is called')
            // if (window.currentRequest != null) {
            //     console.log('request is aborted')
            //     window.currentRequest.abort();
            // }
            // start loader 
            // $('#load-more').loading({
            //     message: input_loader
            // });
            $('#load-more').removeClass('hide');
        },
        success: function (result) {

            console.log('result:', result)
            
            // $('#load-more').loading('stop');
            try {
                
                // var news = JSON.parse(result);

                window.totalPage = result.last_page;

                var count = 0;
                result.data.forEach(element => {
                // news.forEach(element => {
                    
                    count++;
                    console.log('count:', count)

                    // let title = (element.title.length > 50) ? (element.title.substring(0, 50)+'..') : element.title;

                    let article = '<article class="blog_item">' +
                        '<div class="blog_item_img">' +
                            // '<img class="card-img rounded-0" src="'+BASEURL+'/uploads/news/'+element.id+'/medium/'+element.image + '" alt="">' +
                            '<img class="card-img rounded-0" src="' + element.image + '" alt="">' +
                        '</div>' +            
                        '<div class="blog_details">' +
                            '<a class="d-inline-block" href="#">' +
                                // '<h2><a href="'+BASEURL+'/detail/'+element.slug+'">' + title + '</a></h2>' +
                                '<h2><a href="'+BASEURL+'/detail/'+element.slug+'">' + element.title + '</a></h2>' +
                            '</a>' +
                            // '<p>' + $(element.description).text().substring(0, 100) + '...' +
                            '<p>' + element.description +
                                '<a href="'+BASEURL+'/detail/'+element.slug+'"><i class="fa fa-plus"></i> Read More</a>' +
                            '</p>' +
                        '</div>' +
                    '</article>';
                    $(article).insertBefore("#news-box-"+count+":last");

                    if(count==3) {
                        count = 0;
                    }
                });
            } catch(e) {
                //JSON parse error, this is not json (or JSON isn't in your browser)
                console.error(e);
                $('#load-more').html(result);
            }

            $('#load-more').addClass('hide');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.error('Something went wrong.');
            console.error("Status: " + textStatus); 
            console.error("Error: " + errorThrown); 
            $('#load-more').addClass('hide');
            // SHOW MESSAGE
            // $('#load-more').loading('stop');
        },
        complete: function () {
            // Remove spinning icon
            console.log('Request completed');
            $('#load-more').addClass('hide');
            // $('#load-more').loading('stop');
        }        
    });
}
