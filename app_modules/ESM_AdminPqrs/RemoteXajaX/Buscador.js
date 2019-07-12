function Buscador(obj){
    this._config = obj || {};
    this.buscando = false;
    this.seleccion = {};
    
    if(!obj.mincaracteres){
        this._config.mincaracteres = 5;
    }
    
    if(!this._config.llamado){
        throw "Debe especificar una funcion xajax";
    }
    
      if(!obj.prompt){
        throw "Debe especificar la llave del objeto remoto  a mostrar en el listado";
    }
    
}

Buscador.prototype.datos = [];

Buscador.prototype.setElement = function(el){
    return true;//se comenta porq se realiza otro metodo
   this._el;//=[]
     var me = this;
      this._el
     if(el){
          this._el=el  
     } else {
         return;
     }
     
    this.position = [];
    
    //eventos para el elemento
    this._el.onkeyup = function(evt) {
         me._onKeyUp();         
    };
    
    this._el.onblur = function(e){
        //el timer es util para permitir al usuario dar click en un elemento antes que se cierre el menu
        var time = setTimeout(function(){
            me._cerrarMenu();
            clearTimeout(time);
        }, 400);
        
    };

//    this._el.onfocus = function(){
//        me.realizarBusquedad();
//    };
    
    window.onresize = function(event) {
         me._el.onblur();
    };
    
    this.position = this.encontrarPosicion(); 
    this._limpiarMenu();
    this._cerrarMenu();
};

Buscador.prototype._onKeyUp = function(){
    this.realizarBusquedad();
};

Buscador.prototype.realizarBusquedad = function(){
    this.seleccion = {};
    if(this._el.value.length >= this._config.mincaracteres && !this.buscando){
        var value = this._el.value;
       eval(this._config.llamado+"(value)");
       this.buscando = true;
    } else {
        this._limpiarMenu();
    }
};

Buscador.prototype.resultadoBusquedad = function(datos){
    return true;
    this.buscando = false;
  
    
    if(this._el.value.length === 0){
        //console.log("no es necesario dibujar lo que se trajo")
        return;
    }
    
     var left = this._el.offsetLeft;
    var right = this._el.offsetTop;
    var me  = this;
    this.datos = datos;
    
    if(datos.length > 0){
        this._crearMenu();
    }
    
   
    for(var i in datos){
        var li=document.createElement('div');
        li.innerHTML = datos[i].descripcion;
        li.style.cursor = "hand";
        li.style.border = "1px solid #F8F8F8 ";
        li.style.padding = "4px";
        li.style.cursor = "pointer";
         this.ul.appendChild(li);
         li.datos = datos[i];
         
         //eventos elementos
         li.onclick = function(){
            me.clickenelemento(this);
         };
    }

};

Buscador.prototype.clickenelemento = function(el){
    this._el.value = el.datos.descripcion;
    this.seleccion = el.datos;
     this._cerrarMenu();
};
/** logica del menu**/

Buscador.prototype._crearMenu = function(){
    var me = this;
 
 //validar si existe el  menu
    if(!document.getElementById("listabuscador1")){
         this.ul = document.createElement('div');
         this.ul.setAttribute("id", "listabuscador1");
         this.ul.style.position = "absolute";
         this.ul.style.backgroundColor = "white";
         this.ul.style.width = this._el.offsetWidth+"px";
         this.ul.style.zIndex = 33;
         this.ul.style.fontSize = "10px";
         document.body.appendChild(this.ul);
    }
    
    this._limpiarMenu();
    this._abrirMenu();
};

Buscador.prototype.ajustarPosicionMenu = function(){
          this.position =  this.encontrarPosicion();
          this.ul.style.left = this.position[0] -4;
          this.ul.style.top = this.position[1] + this._el.offsetHeight + 5;
};

Buscador.prototype._limpiarMenu = function(){
    if(this.ul){
         this.ul.innerHTML = "";
    }
   
};

Buscador.prototype._abrirMenu = function(){
        this.ul.style.display = "block";
        this.ajustarPosicionMenu();
};

Buscador.prototype._cerrarMenu = function(){
    if(this.ul){
        this.ul.style.display = "none";
    }      
};

//encuntra la posicion del elemento 
Buscador.prototype.encontrarPosicion = function () {
        var curleft = curtop = 0;
        
        var obj = document.getElementById(this._el.id);
        if (obj.offsetParent) {
                do {
                        curleft += obj.offsetLeft;
                        curtop += obj.offsetTop;
                 } while (obj = obj.offsetParent);
        }
        
        return [curleft,curtop];
        
};

var bandera=0;
function autoCompletado(event,nombre,i){
    var busqueda=document.getElementById(nombre+i).value;
    if (event.keyCode == 13) {   
          xajax_autoCompletado(nombre,i,busqueda);
       
    }
}

function agrega_producto(codigo_producto,descripcion,i){
   document.getElementById('nombreproducto'+i).value=descripcion;    
   document.getElementById('productoid'+i).value=codigo_producto;    
   document.getElementById('autocom'+i).innerHTML="";    
}