/***
@title:     Live Search
@version:   2.0
@author:    Andreas Lagerkvist
@date:      2008-08-31
@url:       http://andreaslagerkvist.com/jquery/live-search/
@license:   http://creativecommons.org/licenses/by/3.0/
@copyright: 2008 Andreas Lagerkvist (andreaslagerkvist.com)
@requires:  jquery, jquery.liveSearch.css
@does:      Use this plug-in to turn a normal form-input in to a live ajax search widget. The plug-in displays any HTML you like in the results and the search-results are updated live as the user types.
***/
window.cunt =1;
jQuery.fn.liveSearch=function(conf){
    var config=jQuery.extend({
        //base config
        url:'/search-results.php?q=',
        id:'jquery-live-search',
        duration:400,
        typeDelay:200,
        loadingClass:'loading',
        onSlideUp:function(){},
        uptadePosition:false
    },conf);
    // jquery wrapped form container
    var liveSearch=jQuery('#'+config.id);
    if(!liveSearch.length){
        liveSearch=jQuery('<div id="'+config.id+'"></div>').appendTo(document.body).hide().slideUp(0);
        jQuery(document.body).click(function(event){
            var clicked=jQuery(event.target);
            if(!(clicked.is('#'+config.id)||clicked.parents('#'+config.id).length||clicked.is('input'))){
                liveSearch.slideUp(config.duration,function(){
                    config.onSlideUp()
                    })
                }
            })
    }
    return this.each(function(){
    var input=jQuery(this).attr('autocomplete','off');
    var liveSearchPaddingBorderHoriz=parseInt(liveSearch.css('paddingLeft'),10)+parseInt(liveSearch.css('paddingRight'),10)+parseInt(liveSearch.css('borderLeftWidth'),10)+parseInt(liveSearch.css('borderRightWidth'),10);
    var repositionLiveSearch=function(){
        var tmpOffset=input.offset();
        var inputDim={
            left:tmpOffset.left,
            top:tmpOffset.top,
            width:input.outerWidth(),
            height:input.outerHeight()
            };
            
        inputDim.topPos=inputDim.top+inputDim.height;
        inputDim.totalWidth=inputDim.width-liveSearchPaddingBorderHoriz;
        liveSearch.css({
            position:'absolute',
            left:inputDim.left+'px',
            top:inputDim.topPos+'px',
            width:inputDim.totalWidth+'px'
            })
        };
        
    var showLiveSearch=function(){
        repositionLiveSearch();
        $(window).unbind('resize',repositionLiveSearch);
        $(window).bind('resize',repositionLiveSearch);
        liveSearch.slideDown(config.duration)
        };
        
    var hideLiveSearch=function(){
        liveSearch.slideUp(config.duration,function(){
            config.onSlideUp()
            })
        };
        
    input.focus(function(){
        if(this.value!==''){
            if(liveSearch.html()==''){
                this.lastValue='';
                input.keyup()
                }else{
                setTimeout(showLiveSearch,1)
                }
            }
    }).keyup(function(){
    if(this.value!=this.lastValue){
        input.addClass(config.loadingClass);
        var q=this.value;
        if(this.timer){
            clearTimeout(this.timer)
            }
            this.timer=setTimeout(function(){
            jQuery.get(config.url+q,function(data){
                input.removeClass(config.loadingClass);
                if(data.length&&q.length){
                    liveSearch.html(data);
                    showLiveSearch()
                    }else{
                    hideLiveSearch()
                    }
                })
        },config.typeDelay);
    this.lastValue=this.value
    }
})
})
};


///////

/***
@title:     Live Search
@version:   2.0
@author:    Andreas Lagerkvist
@date:      2008-08-31
@url:       http://andreaslagerkvist.com/jquery/live-search/
@license:   http://creativecommons.org/licenses/by/3.0/
@copyright: 2008 Andreas Lagerkvist (andreaslagerkvist.com)
@requires:  jquery, jquery.liveSearch.css
@does:      Use this plug-in to turn a normal form-input in to a live ajax search widget. The plug-in displays any HTML you like in the results and the search-results are updated live as the user types.
***/
jQuery.fn.liveSearch=function(conf){
    var config=jQuery.extend({
        url:'/search-results.php?q=',
        moduleUrl: '/search/module-ajax/',
        tagUrl: '/search/tag-ajax/',
        id:'jquery-live-search',
        duration:400,
        typeDelay:200,
        loadingClass:'loading',
        onSlideUp:function(){},
        uptadePosition:false
    },conf);
    var liveSearch=jQuery('#'+config.id);
    if(!liveSearch.length){
        liveSearch=jQuery('<div id="'+config.id+'"></div>').appendTo(document.body).hide().slideUp(0);
        jQuery(document.body).click(function(event){
            var clicked=jQuery(event.target);
            if(!(clicked.is('#'+config.id)||clicked.parents('#'+config.id).length||clicked.is('input'))){
                liveSearch.slideUp(config.duration,function(){
                    config.onSlideUp()
                })
            }
        })
    }
    return this.each(function(){
        var input=jQuery(this).attr('autocomplete','off');
        var liveSearchPaddingBorderHoriz=parseInt(liveSearch.css('paddingLeft'),10)+parseInt(liveSearch.css('paddingRight'),10)+parseInt(liveSearch.css('borderLeftWidth'),10)+parseInt(liveSearch.css('borderRightWidth'),10);
        var repositionLiveSearch=function(){
            var tmpOffset=input.offset();
            var inputDim={
                left:tmpOffset.left,
                top:tmpOffset.top,
                width:input.outerWidth(),
                height:input.outerHeight()
            };
            
            inputDim.topPos=inputDim.top+inputDim.height;
            inputDim.totalWidth=inputDim.width-liveSearchPaddingBorderHoriz;
            liveSearch.css({
                position:'absolute',
                left:inputDim.left+'px',
                top:inputDim.topPos+'px',
                width:inputDim.totalWidth+'px'
            })
        };
        
        var showLiveSearch=function(){
            repositionLiveSearch();
            $(window).unbind('resize',repositionLiveSearch);
            $(window).bind('resize',repositionLiveSearch);
            liveSearch.slideDown(config.duration)
        };
        var  bindTagHandlers=function(){
            console.log('bind')
            return $('.search-tag').click(tagClickHandler);
        };
        var tagClickHandler = function(){
            console.log('ClickHandler')
            var query = this.value;
            addQueryToInput(query);
            submitForm();
        }
        var addQueryToInput= function(query){
            console.log('addtoinput')
            return $('.search-query').value = query;  
        };
        var submitForm = function(){
            var query = $(this).find('.search-query')[0].value;
            console.log(query);
            $.get('/search/module-ajax/'+query).done(function(data) {
                $('#search-results-wrapper').html(data)
            });
        };
        var hideLiveSearch=function(){
            liveSearch.slideUp(config.duration,function(){
                config.onSlideUp()
            })
        };
        
        input.focus(function(){
            if(this.value!==''){
                if(liveSearch.html()==''){
                    this.lastValue='';
                    input.keyup()
                }else{
                    setTimeout(showLiveSearch,1)
                }
            }
        }).keyup(function(event){
            if(this.value!=this.lastValue){
                var searchUrl;
                var keyIsEnter = (event.which === 13);
                if(keyIsEnter){
                    //this is a form submit
                    //stop the get request cause we use ajax
                    console.log(event.preventDefault());
                    searchUrl = config.moduleUrl; 
                    console.log(searchUrl);
                } else {
                    searchUrl = config.tagUrl;
                }
                input.addClass(config.loadingClass);
                var q=$(liveSearch).html();
                if(this.timer){
                    clearTimeout(this.timer)
                }
                this.timer=setTimeout(function(){
                    //add variables for tag route or module route
            
            
                    jQuery.get(searchUrl+q,function(data){
                        input.removeClass(config.loadingClass);
                        if(data.length&&q.length){
                            if(keyIsEnter){
                            } else{
                                console.log('start')
                                liveSearch.html(data);
                                showLiveSearch();
                                bindTagHandlers();
                            }
                        }else{
                            hideLiveSearch()
                        }
                    })
                },config.typeDelay);
                this.lastValue=this.value
            }
        })
    })
};
var submitForm = function(){
    var query = $(this).find('.search-query')[0].value;
    console.log(query);
    $.get('/search/module-ajax/'+query).done(function(data) {
        $('#search-results-wrapper').html(data)
    });
}
jQuery(function(){
    $('#live-search').submit(function(event){
       
        event.preventDefault();
       
        var query = $(this).find('.search-query')[0].value;
        console.log(query);
        $.get('/search/module-ajax/'+query).done(function(data) {
            $('#search-results-wrapper').html(data)
        });
        
      
    })
})