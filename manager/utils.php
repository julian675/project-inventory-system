<?php
function updateStatus($conn, $id) {
    $conn->query("
        UPDATE inventory SET status = 
        CASE 
            WHEN quantity >= 500 THEN 'Good'
            WHEN quantity > 200 THEN 'Warning'
            ELSE 'Critical'
        END
        WHERE id = $id
    ");
}
