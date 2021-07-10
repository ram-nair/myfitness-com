<?php

return [
    'something_wrong' => 'Something went wrong!',
    'unauthorized' => 'Unauthorized',
    'device_token_updated' => 'Device token updated',
    'token' => [
        'expired' => 'Your session expired. Please login',
        'invalid' => 'Ivalid Token',
        'blacklisted' => 'Token blacklisted',
        'notexist' => 'Authorization token required',
    ],
    'cards' => [
        'card_added' => 'Credit card successfully added',
        'add_card_failed' => 'Card details are not supported',
        'invalid_card' => 'Invalid credit card',
        'card_exist' => 'Credit card already exists',
        'set_default_success' => 'Card successfully set as default card',
        'unable_to_delete_default_card' => 'Unable to delete default card',
        'card_not_exist' => 'Credit card not found',
        'card_updated' => 'Credit card successfully updated',
        'card_disabled' => 'Credit card successfully deleted',
        'disable_card_failed' => 'Disable card failed',
        'is_active' => 'Cannot delete.You have set this card as your active card',
        'card_assigned_in' => "At least one credit card is required to maintain your account. Add another card before deleting this one",
    ],
    'required_fields' => 'Please complete all mandatory fields',
    'success' => 'Success',
    'registration_success' => 'Registration completed',
    'invalid_credentials' => 'These credentials do not match our records',
    'login_success' => 'Successfully logedin',
    'no_data' => 'There is no data to display',
    'data_updated' => 'Updated successfully',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds',
    'user_exists' => 'This user already exists',
    'user_not_exist' => 'This user not exists',
    'password_reset_send' => 'We have e-mailed your password reset link!',
    'password_reset_failed' => 'Email could not be sent to this email',
    'upload_failed' => 'Failed to process the file',
    'store_list' => "List of stores",
    'no_store_list' => "No store available in your area",
    'slot_list' => 'Available time slots',
    'order' => [
        'yes_slot' => 'Time slot is available',
        'location_notnear' => 'No store available in your area',
        'order_cancel_error' => "Order can't cancel ,it is under process",
        'no_address' => 'No address found',
        'order_data' => 'Order Items',
        'no_slot' => 'Slot is not available, please choose another one',
        'timeout' => 'Substitution replacement time has expired, we are processing the order with the available products',
        'outofstock' => 'Out of stock',
        'list' => "Order List",
        'already_processed' => "This order is already processed",
        're_prob' => "The problem has been reported to the store successfully",
        'invalid_quantity' => "Invalid quantity.",
        'status_change' => [
            'title' => "Your order status is :status",
            'body' => "Order :order status is now :status",
        ],
        'item_outofstock' => [
            'title' => "Some item(s) is/are currently not available on :order",
            'body' => "Some of the items in your cart are out of stock, select substitutions",
        ],
    ],
    'address' => [
        'list' => 'Address list',
        'saved' => 'Address saved',
        'delete' => 'Address deleted',
    ],
    'location_notnear' => 'This store does not deliver to your address',
    'order_cancel_error' => "Order cannot be canceled ,it is under process",
    'no_address' => 'No address found',
    'order_data' => 'Order Items',
    'no_slot' => 'Time slot is not available, please choose another one',
    'yes_slot' => 'Time slot is available',
    'cart' => [
        'list' => "Cart listing",
        'add_error' => "Cart add failed",
        'added' => "Item added to cart succesfully",
        'store_product_not_exists' => "Invalid store or product",
        'clear_all' => "Cart Cleared",
        'list_empty' => "Cart empty",
        'deleted' => "Cart deleted",
        'QUANTITYEXCEED' => "Quantity exceeded",
        'OUTOFSTOCK' => "Out of stock",
    ],
    'slots' => [
        'list' => "Slots List",
    ],
    'products' => [
        'list' => 'Store products list',
    ],
    'stores' => [
        'list' => 'Store list',
    ],
    'favourites' => [
        'list' => 'Favourite stores list',
    ],
    'category' => [
        'list' => 'Category list',
    ],
    'banner' => [
        'list' => 'Banners List',
    ],
    'login' => [
        'error' => 'Unknown error occured',
    ],
    'customer' => [
        'notifications' => "List Notifications",
        'notification' => "Notification Detail",
    ],
    'order_status' => [
        'ECOM' => [
            'Pending' => "Pending",
            'Processing' => "Processing",
            'Completed' => "Completed",
            'Delivered' => "Delivered",
            'Cancel' => "Cancelled",
        ],
       
    ],
    'payment_methods' => [
        'credit_card' => "Online Payment",
        'online' => "Online Payment",
        'cash' => "Cash On Delivery",
        'cod' => "Cash On Delivery",
        'bring_card' => "Bring Card Reader",
        'card_reader' => "Bring Card Reader",
        'cash_on_delivery' => "Cash On Delivery",
        'pay_at_counter' => "Pay At Counter"
    ],
    'classes' => [
        'list' => "Classes List",
        'slots' => "Slots List",
        'yes_slot' => 'Slot is available',
        'no_slot' => 'Slot is not available, please choose another one',
        'cancel_timeout' => 'Sorry, cancellation time for this class is over',
        'order_slot_cancelled' => 'Selected slot cancelled',
        'order_cancelled' => 'Selected class is cancelled',
        'issue_added' => "Issue reported",
        'issue_error' => "Error occurred",
    ],
];
