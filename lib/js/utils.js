function set_option_from_value(select_element, value) {
    for(var i=0; i < select_element.options.length; i++) {
        if (select_element.options[i].value === value) {
            select_element.selectedIndex = i;
            return;
        }
    }
    select_element.selectedIndex = 0;
}