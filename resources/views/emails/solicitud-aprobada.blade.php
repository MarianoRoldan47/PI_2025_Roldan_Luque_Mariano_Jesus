<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Aprobada</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e1e1e1;">

        <div style="background: #1e293b; color: #ffffff; text-align: center; padding: 20px;">
            <h1 style="color: #22a7e1; margin-top: 10px; margin-bottom: 0;">CyberStock WMS</h1>
            <p style="color: #ffffff; font-size: 16px; margin-top: 5px;">Sistema de gestión de almacén</p>
        </div>


        <div style="padding: 20px;">
            <h2>¡Hola {{ $notifiable->name }}!</h2>

            <div
                style="background: #d1e7dd; border-left: 4px solid #198754; padding: 15px; margin: 20px 0; border-radius: 5px; color: #0f5132;">
                <p style="margin: 5px 0; font-size: 18px;"><strong>¡Felicidades!</strong></p>
                <p style="margin: 10px 0;">Tu cuenta ha sido aprobada por nuestro equipo de administradores.</p>
                <p style="margin: 10px 0;">Ahora puedes acceder a todas las funcionalidades del sistema CyberStock WMS.
                </p>
            </div>

            <p>Tu información de usuario:</p>
            <div
                style="background: #2c3034; border-left: 4px solid #22a7e1; padding: 15px; margin: 20px 0; border-radius: 5px;">
                <p style="margin: 5px 0; color: #ffffff;"><strong>Nombre:</strong> {{ $notifiable->name }}
                    {{ $notifiable->apellido1 }} {{ $notifiable->apellido2 }}</p>
                <p style="margin: 5px 0; color: #ffffff;"><strong>Email:</strong> {{ $notifiable->email }}</p>
                <p style="margin: 5px 0; color: #ffffff;"><strong>Rol:</strong> {{ $notifiable->rol }}</p>
            </div>


            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginLink }}"
                    style="background: #198754; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: bold; display: inline-block;">Iniciar
                    Sesión</a>
            </div>

            <p>Si estás teniendo problemas con el botón, puedes copiar y pegar este enlace en tu navegador:</p>
            <p><a href="{{ $loginLink }}" style="color: #22a7e1;">{{ $loginLink }}</a></p>
        </div>


        <div
            style="background: #f5f5f5; padding: 15px; text-align: center; font-size: 14px; color: #6c757d; border-top: 1px solid #e1e1e1;">
            <p>Saludos,<br>El equipo de CyberStock WMS</p>
        </div>
    </div>
</body>

</html>
