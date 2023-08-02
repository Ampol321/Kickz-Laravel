$(document).ready(function () {
    $('#search-text').on('input', function () {
        var searchTerm = $(this).val();
        if (searchTerm.trim() === '') {
            $('#search-results').empty();
        } else {
            $.ajax({
                url: '/api/search',
                type: 'GET',
                data: {
                    term: searchTerm
                },
                success: function (data) {
                    displaySearchResults(data);
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    });

    function displaySearchResults(data) {
        $('#search-results').empty();
        $('#search-results').append(
            '<div class="text-center" style="margin-top:100px"><h1>Search</h1>There are ' + data
                .searchResults.length + ' results.</div>');
        data.searchResults.forEach(function (searchResult) {
            var resultHtml =
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<h4 class="mb-1"><a href="' + searchResult.url + '">' + searchResult.title +
                '</a></h4>' +
                '<div class="font-13 text-success mb-3">' + searchResult.url + '</div>' +
                '</div>' +
                '</div><hr>';
            $('#search-results').append(resultHtml);
        });
    }
});

$(function () {
    let products = [];
    $.ajax({
        url: "/api/productTable",
        type: "get",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        },
        dataType: "json",
        success: function (data) {
            $.each(data, function (index, value) {
                products.push(value.product_name)
            })
            console.log(products);

        },
        error: function () {
            alert("sdad")
        }

    })
    $("#search-text").autocomplete({
        source: products,
        autoFocus: true,
        appendTo: "#autocomplete-dropdown",
    });
    // var products = [

    //     "Nike Zoom Fly",
    //     "Nike SB Dunk Lobsters",
    //     "Nike Airforce 1",
    //     "Jordan 4 Ama Maniére",
    //     "Nike Airmax 97",
    //     "Jordan 1 High",
    //     "Adidas Foam Runners",
    //     "Adidas Superstar",
    //     "Adidas Stan Smith",
    //     "Top Sneakers",
    //     "Adidas Forum Low",
    //     "Adidas Adifom",
    //     "Authentic Checkered",
    //     "Old Skool Highs",
    //     "Old Skool Lows",
    //     "Checkerboard",
    //     "Vans Authentic",
    // ];

});