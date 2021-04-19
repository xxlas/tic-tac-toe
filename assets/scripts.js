function ticTacBoardClick()
{
    $(".tic-tac-square").click(function () {
        $("#selectedPos").val($(this).prop('id'));
        $("#tic-tac-form").submit();
    });
}
