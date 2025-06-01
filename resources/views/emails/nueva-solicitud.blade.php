<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva solicitud de acceso</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e1e1e1;">
        <!-- Cabecera -->
        <div style="background: #1e293b; color: #ffffff; text-align: center; padding: 20px;">
            <h1 style="color: #22a7e1; margin-top: 10px; margin-bottom: 0;">CyberStock WMS</h1>
            <p style="color: #ffffff; font-size: 16px; margin-top: 5px;">Sistema de gestión de almacén</p>
        </div>

        <!-- Contenido -->
        <div style="padding: 20px;">
            <h2>¡Hola {{ $notifiable->name }}!</h2>
            <p>Se ha registrado un nuevo usuario que requiere aprobación:</p>

            <div
                style="background: #2c3034; border-left: 4px solid #22a7e1; padding: 15px; margin: 20px 0; border-radius: 5px;">
                <p style="margin: 5px 0; color: #ffffff;"><strong>Nombre:</strong> {{ $user->name }}
                    {{ $user->apellido1 }} {{ $user->apellido2 }}</p>
                <p style="margin: 5px 0; color: #ffffff;"><strong>DNI:</strong> {{ $user->dni }}</p>
                <p style="margin: 5px 0; color: #ffffff;"><strong>Email:</strong> {{ $user->email }}</p>
                <p style="margin: 5px 0; color: #ffffff;"><strong>Teléfono:</strong> {{ $user->telefono }}</p>
            </div>

            <p>Por favor, revisa esta solicitud y apruébala o recházala según corresponda.</p>

            <!-- Botón -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $enlaceSolicitudes }}"
                    style="background: #22a7e1; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: bold; display: inline-block;">Revisar
                    solicitudes pendientes</a>
            </div>

            <p>Si estás teniendo problemas con el botón, puedes copiar y pegar este enlace en tu navegador:</p>
            <p><a href="{{ $enlaceSolicitudes }}" style="color: #22a7e1;">{{ $enlaceSolicitudes }}</a></p>
        </div>

        <!-- Pie -->
        <div
            style="background: #f5f5f5; padding: 15px; text-align: center; font-size: 14px; color: #6c757d; border-top: 1px solid #e1e1e1;">
            <p>Saludos,<br>El equipo de CyberStock WMS</p>
        </div>
    </div>
</body>

</html>
