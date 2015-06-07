<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['glob'] = array(
'orderState_1' => "Pending",
'orderState_2' => "Processing",
'orderState_3' => "Unlocked",
'orderState_4' => "Declined",
'orderState_5' => "Failed Fraud Review",
'orderState_6' => "Cancelled",
'accessState_1' => "Pending",
'accessState_2' => "Processing",
'accessState_3' => "Completed",
'accessState_4' => "Declined",
'accessState_5' => "Failed Fraud Review",
'accessState_6' => "Cancelled",
'orderStat_1' => "Processing",
'orderStat_2' => "Unlocked",
'orderStat_3' => "Cancelled",
'repairState_1' => "Pending",
'repairState_2' => "In progress",
'repairState_3' => "Repaired",
'repairState_4' => "Disposed",
'repairState_5' => "In progress (off site)",
'repairState_6' => "Irreparable",
'repairState_7' => "Lodged",
'repairState_8' => "Online-Approval",
'repairState_9' => "Online-Rejection",
'repairState_10' => "Pending Approval",
'repairState_11' => "Pending Parts",
'repairState_12' => "Pending Quote Approval",
'repairState_13' => "Quote Approved",
'repairState_14' => "Refund",
'repairState_15' => "Warranty Return (old) ",

'orderState_1_desc' => "Order has been created and staff members are awaiting payment before any further action will be taken. This order may be automatically cancelled if payment has not been made by a specific time scale.",
'orderState_2_desc' => "Payment may or may have not cleared or the order hasn't been dealt with yet.",
'orderState_3_desc' => "Order has been paid for and dispatched. Goods should arrive shortly. Tracking information may be available.",
'orderState_4_desc' => "Order has been declined. More information may be available in the order notes.",
'orderState_5_desc' => "Payment for the order has failed external/internal fraud review.",
'orderState_6_desc' => "Order has been cancelled. Reasons for order cancellation should show in your order notes. Please note that new orders which have not been paid for within a certain time scale may automatically be cancelled."
);
?>