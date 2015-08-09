<?php
    
    include_once('XTImageEdit.php');
    
    $path = "tmp_images/";
    $target = $path.$_FILES['picture']['name'];
    
    move_uploaded_file($_FILES['picture']['tmp_name'], $target);
    
    $imageEdit = new XTImageEdit();
    
    $out_name = $imageEdit->CreateProfilePicture($target);

?>

<script type="text/javascript">
    window.top.window.HideLoading(<?php echo json_encode($out_name);?>);
</script>