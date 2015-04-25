/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$().ready(function () {

    console.log('Document ready!');

    // dodaje confirm dla linków usuwajacych produkty z koszyka
    $('table a.remove').click(function () {

        return confirm("Czy napewno chcesz usunąć ten produkt?");
    });

    $('a.vote-up, a.vote-down').click(function () {

        var $link = $(this),
            url = $link.attr('href');

        $.getJSON(
            url,
            function (response) {
                if (response.success) {
                    $link.prev().html(response.nbVotes);
                } else {
                    alert(response.message);
                }
            }
        );

        return false;
    });
});