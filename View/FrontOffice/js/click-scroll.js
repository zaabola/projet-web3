var sectionArray = [1, 2, 3, 4, 5, 69];

$.each(sectionArray, function(index, value) {
    var sectionId = '#section_' + value;
    var section = $(sectionId);

    if (section.length === 0) return;

    $(document).scroll(function () {
        var offsetSection = section.offset().top - 154;
        var docScroll = $(document).scrollTop() + 1;

        if (docScroll >= offsetSection) {
            $('.navbar-nav .nav-link').removeClass('active');
            $('.navbar-nav .nav-link:link').addClass('inactive');
            $('.navbar-nav .nav-item .nav-link').eq(index).addClass('active').removeClass('inactive');
        }
    });

    $('.click-scroll').eq(index).click(function (e) {
        const href = $(this).attr('href');

        if (href.startsWith('#')) {
            e.preventDefault();
            var offsetClick = section.offset().top - 154;
            $('html, body').animate({ scrollTop: offsetClick }, 300);
        }
    });
});

