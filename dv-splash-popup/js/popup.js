( function( $ ) {
    $(window).load( function() {
        var loading = 0;
        var frequency = 1;
        var homeUrl = dataArr.home_url;

        if( dataArr.loadingTime > loading ) {
            loading = dataArr.loadingTime*1000;
        }

        if( dataArr.frequency != frequency ) {
            frequency = dataArr.frequency;
        }

        var title = dataArr.title;
        var link = dataArr.link;
        var image = dataArr.image;
        var thumbnail = dataArr.thumbnail;

        console.log( frequency + '/' + loading + '/' + $.cookie('SPopFrequency') + '/' + homeUrl );

        if( $.cookie('SPopFrequency') === undefined || $.cookie('SPopFrequency') === 'undefined' ) {
            console.log( 'pop!' );
            setTimeout( function() {
                $.magnificPopup.open( {
                    items: {
                        src: '<div id="test-modal" class="white-popup"><p><a href="' + link + '" target="_blank" aria-label="' + title + '" title="' + title + '" rel="noopener noreferrer">' + thumbnail + '</a></p><a class="popup-modal-dismiss" href="#" aria-label="Popup Modal Dismiss" title="Popup Modal Dismiss"></a></div>',                        
                        type:'inline',
                        mainClass: 'mfp-with-fade',
                        removalDelay: 1000,
                        midClick: true,
                        preloader: true
                    },
                    modal: true
                } );

                $( 'html' ).css({overflow: 'hidden'});
                $( 'body' ).addClass( 'body-noscroll-class' );
                disableScroll();

                $(document).on('click', '.popup-modal-dismiss', function (e) {
                    e.preventDefault();
                    $.magnificPopup.close();
                    $( 'html' ).css({overflow: 'auto'});
                    $( 'body' ).removeClass( 'body-noscroll-class' );
                    enableScroll();
                });
            }, loading );

            if( frequency == 1 ) {
                console.log( 'is 1' );
                $.cookie( 'SPopFrequency', frequency, { 
                    expires: 1, 
                    path: '/', 
                    domain: homeUrl,
                    // secure: true,
                    raw: true
                });
            } else {
                console.log( 'not 1' );
                $.cookie( 'SPopFrequency', frequency, { 
                    // expires: 0, 
                    path: '/', 
                    domain: homeUrl,
                    // secure: true,
                    raw: true
                });
            }
        } else {
            console.log( 'shut!' );
            if( $.cookie( 'SPopFrequency' ) != frequency ) {
                $.removeCookie( 'SPopFrequency' );
                if( frequency == 1 ) {
                    console.log( 'is 1' );
                    $.cookie( 'SPopFrequency', frequency, { 
                        expires: 1, 
                        path: '/', 
                        domain: homeUrl,
                        // secure: true,
                        raw: true
                    });
                } else {
                    console.log( 'not 1' );
                    $.cookie( 'SPopFrequency', frequency, { 
                        // expires: 0, 
                        path: '/', 
                        domain: homeUrl,
                        // secure: true,
                        raw: true
                    });
                }
            }
        }

        function disableScroll() {
            // Get the current page scroll position
            scrollTop = 
              window.pageYOffset || document.documentElement.scrollTop;
            scrollLeft = 
              window.pageXOffset || document.documentElement.scrollLeft,
  
                // if any scroll is attempted,
                // set this to the previous value
                window.onscroll = function() {
                    window.scrollTo(scrollLeft, scrollTop);
                };
        }
  
        function enableScroll() {
            window.onscroll = function() {};
        }
    } );
} ) ( jQuery );