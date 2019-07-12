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
// NebuCart Engine	                          *
// ********************************************
// DO NOT CHANGE ANYTHING BELOW THIS LINE!    *
// ********************************************

var Cart        = new Array();
var tmpArray    = new Array();
var chargeTax   = false;
var shipDesc    = '';
var cardDesc    = '';
var cardName    = '';
var cardNo      = '';
var cardMonth   = '';
var cardYear    = '';
var postAction  = '';
var delim       = '|';
var paymentWin;
var shopperArray = new Array();
var shipeeArray = new Array();

function CartItem(RECREATE, prodID, qty, desc, price, opt, limit){
	if(RECREATE){
		this.prodID = prodID;
		this.qty    = qty;
		this.price  = price;
		this.desc   = desc;
		this.opt    = opt;
		this.limit  = limit;
	} else {
		this.prodID = prodID;
		this.qty    = qty;
		this.price  = getValue(prodID,'price');
		this.desc   = getValue(prodID,'desc');
		this.opt    = getValue(prodID,'opt');
		tmpXtra     = getExtraCosts(prodID,'opt');
		tmpOpt      = getMoreOptions(prodID,'opt');
		if(tmpOpt != ''){
			if(this.opt == ''){
				this.opt += tmpOpt;
			} else {
				this.opt += ', ' + tmpOpt;
			}
		}
		if(tmpXtra != 0){
			this.price = Number(this.price) + Number(tmpXtra);
		}
		this.limit = getValue(prodID,'limit');
	}
}
function AddItem(ItemNo){
	var madeChange = false;
	var alreadyExists = false;
	var newItem = false;
	var gotQty = false;
	var Qty;
	var inputLocation;
	var verb = 'has';
	inputLocation = eval('document.NC_form.' + ItemNo);
	if(inputLocation.type == 'select-one'){
		Qty = inputLocation.options[inputLocation.selectedIndex].value;
		if(Qty == ''){
			Qty = inputLocation.options[inputLocation.selectedIndex].text;
		}
	} else {
		Qty = inputLocation.value;
	}
	if(!Number(Qty) || Qty.indexOf('.') != -1){
		alert('Please enter a whole\nnumerical value for item quantity.');
		inputLocation.focus();
	} else {
		gotQty = true;
	}
	if(gotQty){
		if(Cart.length > 0){
			for(i=0; i < Cart.length; i++){
				if(Cart[i].prodID == ItemNo){
					var newOption = getValue(ItemNo,'opt');
					var moreOptions = getMoreOptions(ItemNo,'opt');
					if(moreOptions != ''){
						if(newOption == ''){
							newOption = moreOptions;
						} else {
							newOption = + ', ' + moreOptions;
						}
					}
					if(newOption != Cart[i].opt){
						madeChange = false;
						alreadyExists = false;
					} else if(Cart[i].qty != Qty){
						ChangeQty(i,Qty);
						madeChange = true;
						alreadyExists = true;
						break;
					} else {
						alreadyExists = true;
						alert('Item ' + Cart[i].prodID + ', (' + Cart[i].desc + ')\nalready exists in your cart.');
						break;
					}
				}
			}
			if(!madeChange && !alreadyExists){
				newItem = true;
			}
		} else {
			newItem = true;
		}
		if(newItem){
			Cart[Cart.length] = new CartItem(false,ItemNo,Qty);
			if(Qty > Number(Cart[Cart.length - 1].limit) && Cart[Cart.length - 1].limit != ''){
				alert('You may only order up to ' + Cart[Cart.length - 1].limit + ' of\n' + Cart[Cart.length - 1].desc + ' per order.\nQuantity will be changed to limit.');
				ChangeQty(Cart.length - 1,Cart[Cart.length - 1].limit);
			}
			if(supressCart){
				alert('Item ' + Cart[Cart.length - 1].prodID + ', (' + Cart[Cart.length - 1].desc + ')\nhas been added to your cart.');
				cartToCookie();
			} else {
				displayCart();
			}
		}
	}
}
function DeleteItem(arrayNum){
	var deletedItem;
	for(i=0; i < Cart.length; i++){
		if(i != arrayNum){
			tmpArray[tmpArray.length] = Cart[i];
		} else {
			deletedItem = Cart[i];
		}
	}
	Cart = new Array();
	for(i=0; i < tmpArray.length; i++){
		Cart[i] = tmpArray[i];
	}
	tmpArray = new Array();
	if(deletedItem){
		if(supressCart && String(location).indexOf(cartPage) == -1){
			cartToCookie();
			alert('Item ' + Cart[arrayNum].prodID + ', (' + Cart[arrayNum].desc + ')\nhas been deleted from your cart.');
		} else {
			displayCart();
		}
	} else {
		alert('Item not found in cart.');
	}
}
function UpdateItem(arrayNum){
	oldQty = Cart[arrayNum].qty;
	newQty = getValue(Cart[arrayNum].prodID,arrayNum);
	if(newQty == 0){
		DeleteItem(arrayNum);
		return true;
	} else if(!Number(newQty) || newQty < 0){
		alert('Please enter a whole\nnumerical value.');
		return false;
	} else if(newQty != oldQty){
		ChangeQty(arrayNum,newQty);
	}
}
function ChangeQty(arrayNum, newQty){
	var OK = false;
	var underLimit = true;
	if(newQty > Number(Cart[arrayNum].limit) && Cart[arrayNum].limit != ''){
		alert('You may not order more than ' + Cart[arrayNum].limit + '\nof these during a session.\nYour quantity will be changed\nto the maximum amount.');
		Cart[arrayNum].qty = Cart[arrayNum].limit;
	} else {
		Cart[arrayNum].qty = newQty;
	}

	if(supressCart && String(location).indexOf(cartPage) == -1){
		cartToCookie();
		alert('Item ' + Cart[arrayNum].prodID + ', (' + Cart[arrayNum].desc + ')\nhas been updated in your cart.');
	} else {
		displayCart();
	}
}
function getValue(itemID,valType){
	optionVal = '';
	objVal = eval('document.NC_form.' + itemID + '_' + valType);
	if(Number(valType)){
		return objVal.value;
	}
	if(typeof objVal != 'undefined'){
		if(valType.indexOf('opt') != -1){
			switch (objVal.type){
			case 'select-one':
				optionVal = objVal.options[objVal.selectedIndex].value;
				if(optionVal == ''){
					optionVal = objVal.options[objVal.selectedIndex].text;
				}
				if(optionVal.indexOf(delim) != -1){
					optionVal = optionVal.substring(0,optionVal.indexOf(delim))
				}
			break
			case 'select-multiple':
				for(j = 0; j < objVal.length; j ++){

					if(objVal.options[j].selected){
						currVal = objVal[j].value;
						if(currVal == ''){
							currVal = objVal[j].text;
						}
						if(currVal.indexOf(delim) != -1){
							currVal = currVal.substring(0,currVal.indexOf(delim))
						}
						if(optionVal == ''){
							optionVal = currVal;
						} else {
							optionVal = optionVal + ', ' + currVal;
						}
					}

				}
			break
			}
		} else {
			optionVal = objVal.value
		}
	}
	return optionVal;
}
function getMoreOptions(itemID, valType){
	retStr = '';
	for(optCount = 0; optCount < 20; optCount ++){
		optName = valType + String(optCount + 1);
		tmpStr = getValue(itemID,optName);
		if(tmpStr == '' || tmpStr == null){
			break;
		} else {
			if(retStr == ''){
				retStr = tmpStr;
			} else {
				retStr += ', ' + tmpStr;
			}
		}
	}
	return retStr;
}
function getExtraCosts(itemID,valType){
	tmpCost   = 0;
	optionVal = '';

	for(optCount = 0; optCount < 20; optCount ++){

		if(optCount > 0){
			objVal = eval('document.NC_form.' + itemID + '_' + valType + optCount);
		} else {
			objVal = eval('document.NC_form.' + itemID + '_' + valType);
		}

		if(typeof objVal != 'undefined'){
			if(valType.indexOf('opt') != -1){
				switch (objVal.type){
				case 'select-one':
					optionVal = objVal.options[objVal.selectedIndex].value;
					if(optionVal.indexOf(delim) != -1){
						tmpCost += Number(optionVal.substring(optionVal.indexOf(delim)+1,optionVal.length));
					}
				break
				case 'select-multiple':
					for(j = 0; j < objVal.length; j ++){

						if(objVal.options[j].selected){
							currVal = objVal[j].value;
							if(currVal.indexOf(delim) != -1){
								tmpCost += Number(currVal.substring(currVal.indexOf(delim)+1,currVal.length));
							}
						}

					}
				break
				}
			}
		}
	}
	return tmpCost;
}
function DeleteCart(){
	if(confirm('Are you sure you want\nto clear the entire Shopping Cart?')){
		Cart = new Array();
		displayCart();
	}
}
function displayCart(){
	cartToCookie();
	document.location = cartPage;
}
function getShipDetails(){
	var i = getSelectedRadio(document.NC_form.ship_option);
	var shipDetails = shipOptions[i].split(delim);
	shipDesc        = shipDetails[0];
	shipAmt         = shipDetails[1];
	shipPerItem     = shipDetails[2];
}
function getSelectedRadio(radioGroup){
	for(i = 0; i < radioGroup.length; i ++){
		if(radioGroup[i].checked){
			return i;
		}
	}
	return 0;
}
function fillShopperForm(){
	shopperArray = getCookieVal(myStoreName + '_shopper');
	if(shopperArray == null || shopperArray == ''){
			shopperArray = new Array();
	}
	shipeeArray = getCookieVal(myStoreName + '_shipee');
	if(shipeeArray == null || shipeeArray == ''){
			shipeeArray = new Array();
	}
	if(shopperArray.length > 0){
		if(shopperArray[0] != ''){
			document.NC_form.fname.value = shopperArray[0];
		}
		if(shopperArray[1] != ''){
			document.NC_form.lname.value = shopperArray[1];
		}
		if(shopperArray[2] != ''){
			document.NC_form.email.value = shopperArray[2];
		}
		if(shopperArray[3] != ''){
			document.NC_form.add1.value = shopperArray[3];
		}
		if(shopperArray[4] != ''){
			document.NC_form.add2.value = shopperArray[4];
		}
		if(shopperArray[5] != ''){
			document.NC_form.city.value = shopperArray[5];
		}
		if(shopperArray[6] != ''){
			document.NC_form.state.value = shopperArray[6];
		}
		if(shopperArray[7] != ''){
			document.NC_form.zip.value = shopperArray[7];
		}
		if(shopperArray[8] != ''){
			document.NC_form.country.value = shopperArray[8];
		}
		if(shopperArray[9] != ''){
			document.NC_form.phone.value = shopperArray[9];
		}
	}
	if(shipeeArray.length > 0){
		if(shipeeArray[0] != ''){
			document.NC_form.Sfname.value = shipeeArray[0];
		}
		if(shipeeArray[1] != ''){
			document.NC_form.Slname.value = shipeeArray[1];
		}
		if(shipeeArray[2] != ''){
			document.NC_form.Sadd1.value = shipeeArray[2];
		}
		if(shipeeArray[3] != ''){
			document.NC_form.Sadd2.value = shipeeArray[3];
		}
		if(shipeeArray[4] != ''){
			document.NC_form.Scity.value = shipeeArray[4];
		}
		if(shipeeArray[5] != ''){
			document.NC_form.Sstate.value = shipeeArray[5];
		}
		if(shipeeArray[6] != ''){
			document.NC_form.Szip.value = shipeeArray[6];
		}
		if(shipeeArray[7] != ''){
			document.NC_form.Scountry.value = shipeeArray[7];
		}
	}
}
function Validate(orderOption, secureWin){
	var countrySel;
	var AllOk     = true;
	var tmpPhone  = '';
	var phoneChar = new Array('-','(',')',' ');
	var goodChar  = true;
	countrySel = document.NC_form.country;
	shopperArray[0] = document.NC_form.fname.value;
	shopperArray[1] = document.NC_form.lname.value;
	shopperArray[2] = document.NC_form.email.value;
	shopperArray[3] = document.NC_form.add1.value;
	shopperArray[4] = document.NC_form.add2.value;
	shopperArray[5] = document.NC_form.city.value;
	shopperArray[6] = document.NC_form.state.value;
	shopperArray[7] = document.NC_form.zip.value;
	if(countrySel.selectedIndex < 0 || countrySel.selectedIndex == 0){
		alert('Please select your country.');
		AllOk = false;
	} else {
		shopperArray[8] = countrySel.options[countrySel.selectedIndex].text;
	}
	shopperArray[9] = document.NC_form.phone.value;
	for(w = 0; w < shopperArray.length; w++){
		shopperArray[w] = shopperArray[w].trim();
	}
//	if(getAltShipping){
//		if(document.NC_form.diffShip.checked){
//			countrySel = document.NC_form.Scountry;
//			shipeeArray[0] = document.NC_form.Sfname.value;
//			shipeeArray[1] = document.NC_form.Slname.value;
//			shipeeArray[2] = document.NC_form.Sadd1.value;
//			shipeeArray[3] = document.NC_form.Sadd2.value;
//			shipeeArray[4] = document.NC_form.Scity.value;
//			shipeeArray[5] = document.NC_form.Sstate.value;
//			shipeeArray[6] = document.NC_form.Szip.value;
//			if(countrySel.selectedIndex < 0 || countrySel.selectedIndex == 0){
//				alert('Please select your country.');
//				AllOk = false;
//			} else {
//				shipeeArray[7] = countrySel.options[countrySel.selectedIndex].text;
//			}
//			for(w = 0; w < shipeeArray.length; w++){
//				shipeeArray[w] = shipeeArray[w].trim();
//			}
//		}
//	}
	if(shopperArray[0] == '' && AllOk){
		alert('Please fill out the "First Name" field');
		document.NC_form.fname.focus();
		AllOk = false;
	}
	if(shopperArray[1] == '' && AllOk){
		alert('Please fill out the "Last Name" field');
		document.NC_form.lname.focus();
		AllOk = false;
	}
	if(AllOk){
		if(shopperArray[2] == ''){
			alert('Please fill out the "Email" field');
			document.NC_form.email.focus();
			AllOk = false;
		} else {
			if(shopperArray[2].indexOf('@') == -1){
				alert('Please enter a valid Email address.');
				document.NC_form.email.focus();
				AllOk = false;
			}
		}
	}
	if(shopperArray[3] == '' && AllOk){
		alert('Please fill out the "Address" field');
		document.NC_form.add1.focus();
		AllOk = false;
	}
	if(shopperArray[5] == '' && AllOk){
		alert('Please fill out the "City" field');
		document.NC_form.city.focus();
		AllOk = false;
	}
	if(shopperArray[8].toLowerCase() == 'us' ||
	   shopperArray[8].toLowerCase() == 'usa' ||
	   shopperArray[8].toLowerCase() == 'united states' ||
	   shopperArray[8].toLowerCase() == 'unitedstates'){
		if(shopperArray[6] == '' && AllOk){
			alert('Please fill out the "State/Provice" field');
			document.NC_form.state.focus();
			AllOk = false;
		}
		if(shopperArray[7] == '' && AllOk){
			alert('Please fill out the "Postal Code" field');
			document.NC_form.zip.focus();
			AllOk = false;
		}
	}
	if(shopperArray[8] == '' && AllOk){
		alert('Please fill out the "Country" field');
		document.NC_form.country.focus();
		AllOk = false;
	}
	if(shopperArray[9] == '' && AllOk){
		if(shopperArray[9] == ''){
			alert('Please fill out the "Phone Number" field');
			document.NC_form.phone.focus();
			AllOk = false;
		} else {
			for(i = 0; i < shoppArray[9].length; i ++){
				for(j = 0; j < phoneChar.length; j ++){
					if(shopperArray[9].charAt(i) != phoneChar[j]){
						goodChar = true;
					} else {
						goodChar = false;
					}
				}
				if(goodChar){
					tmpPhone = tmpPhone + shopperArray[9].charAt(i);
				}
			}
			if(!Number(tmpPhone)){
				alert('Please enter a valid Phone number');
				document.NC_form.phone.focus();
				allOk = false;
			}
		}
	}
//	if(getAltShipping){
//		if(document.NC_form.diffShip.checked){
//			if(shipeeArray[0] == '' && AllOk){
//				alert('Please fill out the "Shipping First Name" field');
//				document.NC_form.Sfname.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[1] == '' && AllOk){
//				alert('Please fill out the "Shipping Last Name" field');
//				document.NC_form.Slname.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[2] == '' && AllOk){
//				alert('Please fill out the "Shipping Address" field');
//				document.NC_form.Sadd1.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[4] == '' && AllOk){
//				alert('Please fill out the "Shipping City" field');
//				document.NC_form.Scity.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[5] == '' && AllOk){
//				alert('Please fill out the "Shipping State/Province" field');
//				document.NC_form.Sstate.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[6] == '' && AllOk){
//				alert('Please fill out the "Shipping Postal Code" field');
//				document.NC_form.Szip.focus();
//				AllOk = false;
//			}
//			if(shipeeArray[7] == '' && AllOk){
//				alert('Please fill out the "Shipping Country" field');
//				document.NC_form.Scountry.focus();
//				AllOk = false;
//			}
//		}
//	}
	if(AllOk){
		setCookie(myStoreName + '_shopper',shopperArray.join(delim),eval(customerTime),cookiePath,unsecureDomain);
		if(shipeeArray.length > 0){
			setCookie(myStoreName + '_shipee',shipeeArray.join(delim),cookiePath,unsecureDomain);
		}
		routeToPayment(orderOption, secureWin);
	} else {
		//return false;
	}
}
function routeToPayment(orderOption, secureWin){
	if (shopperArray[6].toLowerCase() == myState1 || shopperArray[6].toLowerCase() == myState2){
		chargeTax = true;
	} else {
		chargeTax = false;
	}
	if(useShipOptions){
		getShipDetails();
	}
	killCookie(myStoreName + '_chargeTax');
	killCookie(myStoreName + '_shipDesc');
	killCookie(myStoreName + '_shipAmt');
	killCookie(myStoreName + '_shipPerItem');
	setCookie(myStoreName + '_chargeTax'   ,chargeTax     ,eval(cartTime),cookiePath,unsecureDomain);
	setCookie(myStoreName + '_shipDesc'    ,shipDesc      ,eval(cartTime),cookiePath,unsecureDomain);
	setCookie(myStoreName + '_shipAmt'     ,shipAmt       ,eval(cartTime),cookiePath,unsecureDomain);
	setCookie(myStoreName + '_shipPerItem' ,shipPerItem   ,eval(cartTime),cookiePath,unsecureDomain);
	if(orderOption == '' || orderOption == null){
		document.location = COprintVerify;
	} else {
		if(secureWin && securePath != ''){
			if(securePath.charAt(securePath.length-1) != '/'){
				securePath += '/';
			}
			var securePost;
			var prodID = new Array();
			var qty    = new Array();
			var price  = new Array();
			var desc   = new Array();
			var opt    = new Array();
			var limit  = new Array();
			for(i = 0; i < Cart.length; i ++){
				prodID[i] = Cart[i].prodID;
				qty[i]    = Cart[i].qty;
				price[i]  = Cart[i].price;
				desc[i]   = Cart[i].desc;
				opt[i]    = Cart[i].opt;
				limit[i]  = Cart[i].limit;
			}
			securePost  =  securePath + orderOption + '?';
			securePost += 'NC_A='  + escape(shopperArray.join(delim));
			securePost += '&NC_B=' + escape(shipeeArray.join(delim));
			securePost += '&NC_C=' + escape(shipDesc);
			securePost += '&NC_D=' + escape(shipAmt);
			securePost += '&NC_E=' + shipPerItem;
			securePost += '&NC_F=' + shipPercent;
			securePost += '&NC_G=' + useShipOptions;
			securePost += '&NC_H=' + useShipRules;
			securePost += '&NC_I=' + chargeTax;
			securePost += '&NC_J=' + escape(prodID.join(delim));
			securePost += '&NC_K=' + escape(qty.join(delim));
			securePost += '&NC_L=' + escape(price.join(delim));
			securePost += '&NC_M=' + escape(opt.join(delim));
			securePost += '&NC_N=' + escape(desc.join(delim));
			securePost += '&NC_O=' + escape(limit.join(delim));
			parent.document.location = securePost;
		} else {
			document.location = orderOption;
		}
	}
}
function cartToCookie(){
	for (cc = 0; cc < 10000; cc++){
		cookieVal = getCookieVal(myStoreName + '_item' + cc);
		if(cookieVal != '' && cookieVal != null){
			killCookie(myStoreName + '_item' + cc);
		} else {
			break;
		}
	}
	if(Cart.length > 0){
		for(cc = 0; cc < Cart.length; cc++){
			itemContents  = '';
			itemContents += Cart[cc].prodID + delim;
			itemContents += Cart[cc].qty    + delim;
			itemContents += Cart[cc].desc   + delim;
			itemContents += Cart[cc].price  + delim;
			itemContents += Cart[cc].opt    + delim;
			itemContents += Cart[cc].limit  + delim;
			setCookie(myStoreName + '_item' + cc,itemContents,eval(cartTime),cookiePath,unsecureDomain);
		}
	}
}
function cookieToCart(){
	shopperArray = getCookieVal(myStoreName + '_shopper');
	if(shopperArray == null || shopperArray == ''){
			shopperArray = new Array();
	}
	shipeeArray = getCookieVal(myStoreName + '_shipee');
	if(shipeeArray == null || shipeeArray == ''){
			shipeeArray = new Array();
	}
	chargeTax = getCookieVal(myStoreName + '_chargeTax');
	chargeTax = (chargeTax == 'true') ? true : false;
	shipDesc = getCookieVal(myStoreName + '_shipDesc');
	tmpShipAmt = getCookieVal(myStoreName + '_shipAmt');
	if(tmpShipAmt != null && tmpShipAmt != ''){
		shipAmt = tmpShipAmt;
	}
	tmpShipPerItem = getCookieVal(myStoreName + '_shipPerItem');
	if(tmpShipPerItem != null && tmpShipPerItem != ''){
		tmpShipPerItem = (tmpShipPerItem == 'true') ? true : false;
		shipPerItem    = tmpShipPerItem;
	}
	if(shipPercent || useShipRules){
		shipPerItem = false;
	}
	for (cc = 0; cc < 10000; cc++){
		cookieVal = getCookieVal(myStoreName + '_item' + cc);
		if(cookieVal != '' && cookieVal != null){
			itemVal  = getCookieVal(myStoreName + '_item' + cc);
			Cart[cc] = new CartItem(true,itemVal[0],itemVal[1],itemVal[2],itemVal[3],itemVal[4],itemVal[5]);
		} else {
			break;
		}
	}
}
function getCookie(key){
	theCookie = '';
	if(document.cookie && document.cookie.indexOf(key) != -1){
		theCookie = document.cookie;
		theCookie = theCookie.substring(theCookie.indexOf(key),theCookie.length);
		if(theCookie.indexOf(';') != -1){
			theCookie = theCookie.substring(0,theCookie.indexOf(';'));
		}
		return theCookie;
	}
}
function getCookieVal(key){
	theCookie = getCookie(key);
	if(theCookie != '' && theCookie != null){
		theVal = theCookie.substring(theCookie.indexOf(key) + key.length + 1,theCookie.length);
		if(theVal.indexOf(delim) != -1){
			theVal = theVal.split(delim);
		}
		return theVal;
	} else {
		return '';
	}
}
function setCookie(key,val,exp,path,site){

	theCookie = key + '=' + val;
	if(exp != '' && exp != null){
		theCookie += ';expires=' + exp.toGMTString();
	}
	if(path != '' && path != null){
		theCookie += ';path=' + path;
	}
	if(site != '' && site != null){
		theCookie += ';domain=' + site;
	}
	currLocation = String(location).toLowerCase();
	if(currLocation.indexOf('https') != -1){
		theCookie += ';secure';
	}
	document.cookie = theCookie;

}
function killCookie(key){
	setCookie(key,'',setExp(0,0,0,0,0,0,-2),null,null);
}
function setExp(S,M,H,D,W,Mo,Y){
	exp = new Date();
	sec = 1000;
	min = 60  * sec;
	hr  = 60  * min;
	day = 24  * hr;
	wk  = 7   * day;
	mo  = 30  * day;
	yr  = 365 * day;
	theLen  = exp.getTime();
	theLen += (S * sec) + (M * min) + (H * hr);
	theLen += (D * day) + (W * wk) + (Mo * mo);
	theLen += (Y * yr);
	exp.setTime(theLen);
	return exp;
}
function trim(){
	tmpTxt = this.toString();

	while(tmpTxt.charAt(0) == ' '){
		tmpTxt = tmpTxt.substring(1,tmpTxt.length);
	}
	while(tmpTxt.charAt(tmpTxt.length - 1) == ' '){
		tmpTxt = tmpTxt.substring(0,tmpTxt.length-1);
	}
	return tmpTxt;
}
String.prototype.trim = trim;
cookieToCart();