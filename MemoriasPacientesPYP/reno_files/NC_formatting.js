// NebuCart - The JavaScript Shopping Cart
// by Nebulus Designs
//
// Copyright 1999-2001 all rights reserved.

// None of this script may be redistributed or sold
// without the authors express consent.
// Violations of copyright will be prosecuted.

// If you would like to use NebuCart,
// email us at nebucart@nebulus.org
// or visit http://nebucart.nebulus.org

// ********************************************
// NebuCart Cart Formatting Routines          *
// ********************************************
// cart variables - you edit these to taste   *
// ********************************************

var myFont = fontFace;

function formatDecimal(amt,places){
	var tmpString = new String(amt);
	var strBegin = 0;
	var strEnd = 0;
	var endVal = 0;
	var defaultPlaces = 2;
	if(!Number(amt)){
		return '0.00';
	}
	if(places == '' || places == null || !Number(places)){
		places = defaultPlaces;
	}
	if(tmpString.indexOf('.') != -1){
		strBegin = tmpString.substring(0, tmpString.indexOf('.'));
		if(strBegin == ''){ strBegin = 0; }
		strEnd = tmpString.substring(tmpString.indexOf('.')+1, tmpString.length);
		if(strEnd.length > places){
			keeper = Number('.' + strEnd.substring(0,places));;
			rounder = strEnd.charAt(places);
			if(rounder >= 5){
				adder = '';
				for(inc = 0; inc < places -1; inc ++){
					adder += '0';
				}
				adder = Number('.' + adder + '1');
				strEnd = Number(keeper) + adder;
				tmpString = new String(Number(strBegin) + Number(strEnd));
			}
		}
	}
	if(tmpString.indexOf('.') != -1){
		clipper = tmpString.indexOf('.') + 1;
		strBegin = tmpString.substring(0, clipper);
		if(strBegin.charAt(0) == '.'){ strBegin = '0.'; }
		strEnd = tmpString.substring(clipper, clipper+places);
		if(strEnd.length == 1){ strEnd += '0'; }
		tmpString = strBegin + strEnd;
	} else {
		var zeros = '.'
		for(plcCount = 0; plcCount < places; plcCount++){
			zeros += '0';
		}
		tmpString += zeros;
	}
	return tmpString;
}
currentDate = new Date();
Months      = new Array();
Months[0]   = 'Enero';
Months[1]   = 'Febrero';
Months[2]   = 'Marzo';
Months[3]   = 'Abril';
Months[4]   = 'Mayo';
Months[5]   = 'Junio';
Months[6]   = 'Julio';
Months[7]   = 'Agosto';
Months[8]   = 'Septiembre';
Months[9]   = 'Octubre';
Months[10]  = 'Noviembre';
Months[11]  = 'Diciembre';
today       = Months[currentDate.getMonth()];
if(navigator.appName == 'Netscape'){
	var tmpYr = String(currentDate.getYear());
	today = today + ' ' + currentDate.getDate() + ', 20' + tmpYr.substring(tmpYr.length-2,tmpYr.length);
} else {
	today = today + ' ' + currentDate.getDate() + ', ' + currentDate.getYear();
}
function getShipRule(amt,qty){
	var custCountry   = shopperArray[8].toLowerCase();
	var countryList   = new Array();
	var isDomestic    = false;
	var applyDomestic = false;
	var percent       = false;
	var inBounds      = false;
	var useQty        = false;
	var useAmt        = false;
	var ruleMatch     = false;
	var shipCost      = 0;
	var theShipping   = 0;
	if(myShipRules.length == 0){
		alert('no Ship Rules');
		return theShipping;
	}
	for(ruleCount = 0; ruleCount < myShipRules.length; ruleCount ++){
		applyDomestic = myShipRules[ruleCount].applyDomestic;
		percent       = myShipRules[ruleCount].percent;
		shipCost      = myShipRules[ruleCount].shipCost;
		amtLbound     = myShipRules[ruleCount].amtLbound;
		amtUbound     = myShipRules[ruleCount].amtUbound;
		qtyLbound     = myShipRules[ruleCount].qtyLbound;
		qtyUbound     = myShipRules[ruleCount].qtyUbound;
		if(!Number(1+percent)   || !Number(1+shipCost)  ||
		   !Number(1+amtLbound) || !Number(1+amtUbound) ||
		   !Number(1+qtyLbound) || !Number(1+qtyUbound)){
		   	return theShipping;
		   	break;
		}
		if(amtLbound == amtUbound){
			useAmt   = false;
			inBounds = false;
		} else {
			if(amtLbound > amtUbound){
				var tmpBound = amtLbound;
				amtLbound = amtUbound;
				amtUbound = tmpBound;
			}
			useAmt = true;
			if((amt >= amtLbound) && (amt <= amtUbound)){
				inBounds = true;
			}
		}
		if(!useAmt){
			if(qtyLbound == qtyUbound){
				useQty   = false;
				inBounds = false;
			} else {
				if(qtyLbound > qtyUbound){
					var tmpBound = qtyLbound;
					qtyLbound = qtyUbound;
					qtyUbound = tmpBound;
				}
				useQty = true;
				if((qty >= qtyLbound) && (qty <= qtyUbound)){
					inBounds = true;
				}
			}
		}
		if(useAmt && useQty){
			useAmt   = false;
			useQty   = false;
			inBounds = false;
			return theShipping;
			break;
		}
		countryList = myShipRules[ruleCount].countries.split('|');
		if(countryList.length == 0){
			return theShipping;
			break;
		}
		for(countryCount = 0; countryCount < countryList.length; countryCount ++){
			if(custCountry.toLowerCase() == countryList[countryCount].toLowerCase()){
				isDomestic = true;
				break;
			}
		}
		if((isDomestic == applyDomestic) && inBounds){
			if(percent){
				theShipping = (shipCost * amt);
			} else {
				theShipping = (shipCost);
			}
			return theShipping;
			break;
		} else {
			isDomestic    = false;
			applyDomestic = false;
			percent       = false;
			inBounds      = false;
			useQty        = false;
			useAmt        = false;
			ruleMatch     = false;
			shipCost      = 0;
			theShipping   = 0;
			countryList   = new Array();
		}
	}
	return theShipping;
}