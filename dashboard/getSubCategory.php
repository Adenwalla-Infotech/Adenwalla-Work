<?php

include("../includes/_config.php");

if (!empty($_POST["catid"])) {

    $id = intval($_POST['catid']);

    $query = mysqli_query($conn, "SELECT * FROM tblsubcategory WHERE _categoryid=$id ");
?>
    <option value="">Select Subcategory</option>
    <?php
    while ($row = mysqli_fetch_array($query)) {
    ?>
        <option value="<?php echo htmlentities($row['_id']); ?>"><?php echo htmlentities($row['_subcategoryname']); ?></option>
<?php
    }
}

?>