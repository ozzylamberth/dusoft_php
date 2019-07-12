
function setDate(elindex, date_format, lang){

    var make_time = 0;
	var actual = '';
	
    /* Prepare the language dependent shortcuts */
	switch(lang.toLowerCase())
	{
	   case 'de': today = 'h';  // h = heute
	              yesterday = 'g';  // g = gestern
				  break;
	   case 'it': today = 'o';       // o = oggi
	              yesterday = 'i';   // i = ieri
				  break;
	   case 'es':  today = 'h';       // h = hoy
	              yesterday = 'a';   // a = ayer
				  break; 
	   case 'fr':  today = 'a';       // h = aujourd'hui
	              yesterday = 'h';   // a = hier
				  break; 
	  
	   default: today = 't';         // t = today
	            yesterday = 'y';     // y = yesterday
	}
	 
	/* Extract the value of the input element an convert to lower case to be sure */
	buf = elindex.value;
    buf = buf.toLowerCase();
	buf = buf.charAt(buf.length - 1);
	
	/* Check whether it is a possible shortcut */
	if (((buf<".")|| (buf > "9")) && (buf!="/") && (buf!='-'))
    {
	    /* Get the date today */
	    jetzt=new Date();
	    datum=jetzt.getDate();
	    monat=jetzt.getMonth();
	    jahr=jetzt.getFullYear();
	
		if(buf == today)
		{ 
		    make_time = 1;
		}
	    else if(buf == yesterday)  //* If yesterday, move one day backwards
		{
			datum--;
			
			if(datum<1)
			{
				datum = 1;
				monat--;
				
				if(monat<0)
				{
					monat = 0; 
					jahr--;
				}
			}
			
			make_time =1;
		}
		else
		 {
		       actual=''; //* Set to empty to erase the input
		 }

	    
		//* If a short cut compose date according to format 
		if(make_time == 1)
		{
		
		    //* Adjust month to correspond to 12 = December, 1 = January, etc. 
	        monat++; 
			
			//* Adjust the day and month to show 1= '01' etc. 
		    if (datum<10) datum="0" + datum;
	        if (monat<10) {monat="0" + monat;}
		

	        //* Now compose the date according to the format
		    switch(date_format.toLowerCase())
		    {
		        case 'yyyy-mm-dd': actual = jahr + '-' + monat + '-' + datum;
				                    break;
						case 'dd.mm.yyyy':  actual = datum + '.' + monat + '.' + jahr;
				                    break;
			//	case 'dd/mm/yyyy':  actual = monat + '/' + datum + '/' + jahr;
						case 'dd/mm/yyyy':  actual = datum + '/' + monat + '/' + jahr;
				                   break;
	            default: actual = jahr + '-' + monat + '-' + datum; //* Default format is the standard yyyy-mm-dd 
			}
			
	   }		
	
	    elindex.value=actual; //* Now set the value of the element
	    return true;
	}
	else
	{
	   return false;
	}
}


