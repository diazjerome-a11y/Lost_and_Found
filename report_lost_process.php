$item_name = $_POST['item_name'] === "Other" 
    ? $_POST['custom_item'] 
    : $_POST['item_name'];

$color = $_POST['color'] === "Other" 
    ? $_POST['custom_color'] 
    : $_POST['color'];

$location = $_POST['location_found'] === "Other" 
    ? $_POST['custom_location'] 
    : $_POST['location_found'];