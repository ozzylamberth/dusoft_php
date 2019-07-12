// NebuCart - The JavaScript Shopping Cart
// E-Commerce YOUR way
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
// NebuCart User Defined Settings             *
// ********************************************
// Cart variables - you edit these to taste   *
// ********************************************

// set your company information here. This will all
// generated on the printable form
// Default settings are for Nebulus Designs
/*****************************************
BEGIN CODE
*****************************************/

// set the domain values for your cookies.
// this should allow you to put catalog
// pages in different directories.
// domain values require two "."!
var unsecureDomain = '.siicsalud.com';
var secureDomain   = '.siisalud.com';

// set the path for the cookies
// currently, we set the path as root.
var cookiePath     = '/';

/*****************************************
END CODE
*****************************************/

var myName      = 'Sociedad Iberoamericana de Información Científica';
var mySite      = 'http://www.siicsalud.com/';
var myEmail     = 'imasd@siicsalud.com';
var myPhone     = '(00 54) 11 4342-4901';
var myAddress   = 'Avda. Belgrano 430 - 9º';
var myCityState = 'Buenos Aires';
var myZip       = 'C1092AAR';
var myCountry   = 'ARGENTINA';
var myLogo      = 'http://www.siicsalud.com/main/Imasd.gif';

// this is your prefix for all cookies written by your
// implementation of the cart. Do not use spaces!
var myStoreName = 'SS';

// set customer Info persistence and
// cart persistence via cookies.
// usage: "setExp(S,M,H,D,W,Mo,Y)"
// S = seconds, M = minutes, H = hours
// D = days, W = weeks, Mo = months, Y = years

// note, customer info should persist for a while, say
// 2 to 3 months or longer, but cart data should only
// last a few minutes for the shopping experience.
// default times:
// customer = 2 months,
// cart: 5 mintues
var customerTime = 'setExp(0,0,0,0,0,2,0)';
var cartTime     = 'setExp(0,60,0,0,0,0,0)';

// set this variable to true if you don't want the
// cart page to display each time an item's added.
// if set to true, you will get a pop up instead.
var supressCart  = false;

// set the font face to whatever font you're using on your site
// Default setting is "arial,helvetica"
var fontFace = 'Verdana,Arial,Helvetica';

// cart look and feel
var cartHeader    = 'silver';
var cartRow1      = 'white';
var cartRow2      = '#DDDDDD';
var cartTaxRow    = '#DDDDDD';
var cartShipRow   = '#DDDDDD';
var cartSubRow    = '#DDDDDD';
var cartTotalRow  = 'silver';
var cartBorder    = 0;
var cartCellSpace = 1;
var cartCellPad   = 2;

// set the currency character
var currency = '$';

// set wether you want to gether seperate shipping info
// for WorldPay, set to false
var getAltShipping = true;

// enter the percentag tax to charge customers if
// they reside in your state. example - .06 is 6%
// Default setting is 6.5% for Kansas (where I live).
var stateTax = '.00';

// enter the abreviation and name of the state you live in.
// if the customer lives here, then they will get
// charged the sales tax.
// Important - leave the state abreviation and name in lower case!
// Default setting is ks/kansas
var myState1 = 'ba';
var myState2 = 'buenos aires';

// enter shipping amount in percentage or straight charge.
// if you use a percentage, then set shipPercent = true,
// otherwise set it to false.
// Default setting is 10%
var shipAmt = '0';
var shipPercent = false;

// set shipping cost per item.
// If set to true and shipPercent is false, then the cusomter will
// be charged shipping on a per item basis.
// If set to true, shipPercent is false, and useShipOptions is false,
// then the cusomter will be charged the set shipping amount per item ordered.
var shipPerItem = false;

// set this to true if you want to use shipping
// options as opposed to a set amount or percentage.
// set to false to use a set amount or percentage
// This will override the variables shipAmt, shipPercent, and shipPerItem
var useShipOptions = false;

// set this to true if you want to use shipping
// rules as opposed to a set amount, percentage, or straight shipping options
// shipPercent, shipPerItem, and shipOptions must be false!
var useShipRules = false;

// this will define a set of radio buttons for your shipping options.
// the true/false at the end is for setting that shipping option to
// be charged per each item ordered.
// Formatting:
// One option per line - "Options Description + | + option cost + | true/false",
var shipOptions = new Array(
'UPS Ground|14.95|true',
'UPS Second Day Air|32.50|true',
'FedEx Overnight|40.95|true'
);

// this will define a set of rules for what amount ot charge for shipping
// should you decide not to use options, set amount, percentage, etc.
// Formatting:
// Define a new shipRule per line and pass the proper arguments:
// new shipRule(amtLbound,amtUbound,qtyLbound,qtyUbound,shipCost,percent,countries,applyDomestic)
// amtLbound & amtUbound - the monetary range of the order to apply this rule.
//						   set both  to 0 to disqualify the amount range.
//						   if you don't use amount, then you must use the quantity
//						   bounds for the rule.
// qtyLbound & qtyUbound - the item quantity range of the order to apply this rule
//						   set both  to 0 to disqualify the quantity range.
//						   if you don't use quantity, then you must use the amount
//						   bounds for the rule.
// shipCost              - any numerical value
// percent               - true/false. True charges the shipCost as a percentage of
//						   the order subtotal. False charges as a straight amount
// countries             - the listing of the countries that you consider in your
//                         national shipping area. List must be delimited with a |
// applyDomestic         - true/false. True applies the rule if the customer's
//                         country of residence matches your list and other
//                         qualifiers apply. False applies the rule if the
//                         customer's country does NOT match the list (international)
//                         and other qualifiers apply.
var myShipRules = new Array(
new shipRule(0,   99.99,0,0,4.5,false,'USA|Canada',true),
new shipRule(100,100000,0,0,0.1,true, 'USA|Canada',true),
new shipRule(0,   49.99,0,0,9.0,false,'USA|Canada',false),
new shipRule(50, 100000,0,0,0.2,true, 'USA|Canada',false)
);

// set this option for which credit cards you accept
// use the same formatting as the shipOptions
var cardOption = new Array(
'Visa',
'MasterCard',
'Diners',
'American Express'
);

// set whether you'd like to allow a printable form for orders
// If you don't use a printable form, then make sure you
// have your form or gateway based ording variables set!
// default is true
var usePrint = true;

// set whether you want to allow CGI or ASP form based order submission.
// default is true
var useForm = true;

// set whether you want to allow the
var useGateway = false;

// set wether you want to allow customers the option to use
// unsecure order submission (not including printable forms)
// if useSecure is false and/or you're missing your secure
// page settings, the cart defaults to unsecure ordering
var useUnsecure = false;

// set whether you want to allow secure order submission.
// default is true
var useSecure = true;

// the page that displays the cart contents
// if the cart script is on this page, it will display
// update/delete buttons for your items
// otherwise, it displays as a receipt format.
var cartPage = 'http://www.siicsalud.com/carrito/cart.html';

// set the names of the cart pages
// the page where we get the shipping information
var COstep1 = 'http://www.siicsalud.com/carrito/customer_data.html';

// the last step for printing an order form (verify address, etc.)
var COprintVerify = 'http://www.siicsalud.com/carrito/printverify.html';

// the printable order form
var COprint = 'http://www.siicsalud.com/carrito/printorder.html';

// the page that will build the form data
// for posting to your form handling CGI/ASP
// this will post to the unsecure form handler
// defined by 'unsecurePostAction'
var COform = 'http://www.siicsalud.com/carrito/formorder.html';

// the page that will build the form data
// for credit card validation
// through your merchant account
// this will post to the unsecure form handler
// defined by 'unsecureGatewayAction'
var COgateway = 'http://www.siicsalud.com/carrito/gateway.html';

// the page that will build the form data
// for the secure CGI/ASP order form submission
// This posts the order to your form handling CGI script
// defined by 'securePostAction'
var COsecureForm = 'secure_formorder.html';

// the page tha will build the form data
// for secure credit card validation
// through your merchant account
// this posts the order to the merchant account gateway
// or account handling script defined by 'secureGatewayAction'
var COsecureGateway = 'secure_gateway.html';

// set CGI/ASP Post Action path (non-secure)
// if you aren't using CGI/ASP submission, then set to ""
var unsecurePostAction = 'http://www.siicsalud.com/cgi-bin/af.cgi';

// set the Post Action for your particular
// credit card gateway processor
// see your processor documentation for this address
var unsecureGatewayAction = 'http://www.yoursite.com/carrito/NC_writeorder.cgi';

// set up the Post action for your secure form.
// If you aren't using secure ordering, then set securePostAction = ""
var securePostAction = 'https://www.siicsalud.com/carrito/NC_writeorder.cgi';

// set up the Post action for your secure form.
// If you aren't using secure ordering, then set securePostAction = ""
var secureGatewayAction = 'https://www.siicsalud.com/cgi-bin/af.cgi';

// set the path to your SSL here. If you aren't using
// secure server, then this variable isn't necessary
var securePath = 'https://www.siicsalud.com/carrito/';
//var securePath = 'https://www.siicsalud.com/cgi-bin/'

// set up an array of tags for your specific form handling CGI script
// see the form script help file for required/optional tags
// be sure to use escape charcaters (see example)!
// by default, these tags are specifically coded for use with
// Matt's Script Archive formmail.cgi
var extraFormTags = new Array(
'<input type="hidden" name="recipient" value="centrocomputos@siicsalud.com">',
'<input type="hidden" name="subject" value="Suscripción por artículos">'
);

// set the name of the field that designates the mail recipient for
// your form handling CGI or ASP. This gets used to add the customer's email
// to this field so that they get a copy of the order as well.
// leave blank if you have another method of mailing a receipt to the
// customer
var cgiMailField = 'recipient';

// Shipping Rule Object - Do not edit
function shipRule(amtLbound,amtUbound,qtyLbound,qtyUbound,shipCost,percent,countries,applyDomestic){
	this.amtLbound     = amtLbound;
	this.amtUbound     = amtUbound;
	this.qtyLbound     = qtyLbound;
	this.qtyUbound     = qtyUbound;
	this.shipCost      = shipCost;
	this.percent       = percent;
	this.countries     = countries;
	this.applyDomestic = applyDomestic
}
