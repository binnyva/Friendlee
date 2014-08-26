#!/bin/bash

#zenity --text-info --title="Enter SQL" --text="Enter SQL:" --entry-text ""
sql=$(kdialog --textinputbox "Enter SQL Change" "" 0 300 200)
echo $sql >> $(date '+%Y-%m-%d').sql