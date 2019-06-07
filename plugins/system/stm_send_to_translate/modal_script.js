function stmSubmit()
{
    $form = document.getElementById("adminForm");
    $profile_id = document.getElementById("profile_id").value;
    if ($profile_id) {
        $form.setAttribute("action", "index.php?option=com_st_manager&task=dashboard.add&profile_id=" + $profile_id);
        Joomla.submitform("dashboard.add", $form);
    } else {
        Joomla.renderMessages({"error":["{{ERROR_MESSAGE}}"]});
        document.getElementById("modal-close-btn").click();
    }
}