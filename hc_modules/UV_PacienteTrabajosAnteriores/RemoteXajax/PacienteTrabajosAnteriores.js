/**************************************************************************************
* $Id: PacienteTrabajosAnteriores.js,v 1.1 2009/06/09 19:14:07 hugo Exp $
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

function ResetearEnfermedades()
{
    document.enfermedades.reset();
    document.getElementById('obs_ep').disabled=true;
    document.getElementById('obs_accp').disabled=true;
}


function ResetearTrabajos()
{
    document.trabajos.reset();
}

function ResetearEPS_Anterior()
{
    document.eps_anterior.reset();
}


function llamarCiudades(depto)
{
    xajax_Llamar_ciudades(depto);
}


function GuardarInfo(obs_ep,obs_accp,tipo_id_paciente,paciente_id)
{
        
    enfermedad=obs_ep;
    accidentes=obs_accp;
    tipo_id_paciente
    paciente_id
        for(i=0;i<document.enfermedades.ep.length;i++)
        {
            if(document.enfermedades.ep[i].checked==true)
            break;
        }
        enfermedades_sw=document.enfermedades.ep[i].value;

        for(i=0;i<document.enfermedades.accp.length;i++)
        {
            if(document.enfermedades.accp[i].checked==true)
            break;
        }
        accidentes_sw=document.enfermedades.accp[i].value;


        xajax_GuardarInfo(tipo_id_paciente,paciente_id,enfermedad,accidentes,enfermedades_sw,accidentes_sw);
}



function ValidarE(valor)
{
    if(valor=='1')
    {
        document.getElementById('obs_ep').disabled=false;
        document.getElementById('be').disabled=false;
        
    }
    else
    {
        if(valor=='0')
        {
            document.getElementById('obs_ep').value="";
            document.getElementById('obs_ep').disabled=true;
             for(i=0;i<document.enfermedades.accp.length;i++)
                {
                    if(document.enfermedades.accp[i].checked==true)
                    break;
                }
          
            resultado=document.enfermedades.accp[i].value;
            
            if(resultado=='0')
            {
                document.getElementById('be').disabled=true;
                
            }
            
        }
    }
 
}


function ValidarAcc(valor)
{
    if(valor=='1')
    {
        document.getElementById('obs_accp').disabled=false;
        document.getElementById('be').disabled=false;
    }
    else
    {
        if(valor=='0')
        {   
            document.getElementById('obs_accp').disabled=true;
            document.getElementById('obs_accp').value="";
             for(i=0;i<document.enfermedades.ep.length;i++)
                {
                    if(document.enfermedades.ep[i].checked==true)
                    break;
                }
          
            resultado=document.enfermedades.ep[i].value;
           
            if(resultado=='0')
            {
                document.getElementById('be').disabled=true;
            }

        }
    }
 
}

