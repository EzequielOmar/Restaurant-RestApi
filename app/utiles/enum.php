<?php
abstract class Tipo
{
    const cliente = 1;
    const staff = 2;
}

abstract class Sector
{
    const socio = 1;
    const mozo = 2;
    const bar = 3;
    const cocina = 4;
    const cerveza = 5;
}

abstract class EstadoDeMesa
{
    const abierta = 1;
    const esperando = 2;
    const comiendo = 3;
    const cerrada = 4;
}

abstract class EstadoDePedido
{
    const comandado = 1;
    const preparacion = 2;
    const listo = 3;
    const entregado = 4;
    const cancelado = 5;
    const finalizado = 6;
}

abstract class EstadoDeStaff
{
    const disponible = 1;   //logueado y sin tope de servicios
    const ocupado = 2;  //logueado con tope de servicios
    const activo = 3;   //no logueado, en actividad laboral
    const suspendido = 4;
    const baja = 5;
}

abstract class OperacionStaff
{
    const login = "Login";
    const logout = "Logout";
    const toma = "Toma servicio";
    const despacho = "Despacho";
}