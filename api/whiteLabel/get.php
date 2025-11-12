<?php
header('Content-Type: application/json');

$response = [
    "success" => true,
    "message" => "WL capturada",
    "wl" => [
        "id" => "gjlfpggiddcminhebiejofeglfjmleli",
        "checkout" => "https://watidy.com.br/botao-extensao/?utm_source=botao-extensao&utm_medium=org&utm_term=botao-extensao-watidy&utm_campaign=botao-watidy",
        "tutorial" => "https://www.youtube.com/playlist?list=PLv6vM8bnTk4lElaSeaLRExd6J5S_0XGl5",
        "webhook" => "https://n8n.manyflux.com.br/webhook/novocliente",
        "cor_primaria" => 0,
        "banner" => null,
        "status" => "ACTIVE",
        "install" => "https://watidy.com.br/baixou",
        "uninstall" => "https://watidy.com.br/desinstalou",
        "rewards" => "https://memb.ly/watidy/partner?utm_source=botao_ext",
        "suporte" => "https://api.whatsapp.com/send/?phone=%2B553129424122&text=Ol%C3%A1+desejo+suporte+estou+utilizando+waTidy",
        "webhook_login_clients" => "https://n8n.manyflux.com.br/webhook/64c4296d-61d8-4271-9ff2-1aa44c8a192c",
        "ia_wascript" => "https://ia.wascript.com.br/produto/pacote-premium-2",
        "meetAovivo" => [
            "users" => "ALL",
            "aoVivo" => false,
            "online" => [
                "title" => "📢 Estamos ao Vivo!",
                "btnName" => "🔗 Acesse a apresentação aqui:",
                "urlMeet" => "https://meet.google.com/ffj-xdtc-may",
                "description" => "Hoje teremos nossa live semanal às 15 horas. Já salve nosso link para aprender mais sobre nossa ferramenta!\n\nAproveite para tirar suas dúvidas online!"
            ],
            "offline" => [
                "title" => "",
                "active" => false,
                "description" => "",
                "suportRedirect" => false
            ],
            "fusoHorario" => "America/Sao_Paulo",
            "activationDays" => [
                "sexta" => ["active" => false, "start_time" => "14:30", "finish_time" => "16:00"],
                "terca" => ["active" => false, "start_time" => "", "finish_time" => ""],
                "quarta" => ["active" => false, "start_time" => "11:04", "finish_time" => "11:19"],
                "quinta" => ["active" => false, "start_time" => "", "finish_time" => ""],
                "sabado" => ["active" => false, "start_time" => "", "finish_time" => ""],
                "domingo" => ["active" => false, "start_time" => "00:00", "finish_time" => "00:00"],
                "segunda" => ["active" => false, "start_time" => "", "finish_time" => ""]
            ]
        ],
        "suporte_clientes" => [
            "free" => "https://api.whatsapp.com/send/?phone=%2B553129424122&text=Ol%C3%A1+desejo+suporte+estou+utilizando+waTidy",
            "premium" => "https://api.whatsapp.com/send/?phone=%2B553129424122&text=Ol%C3%A1+desejo+suporte+estou+utilizando+waTidy"
        ]
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
