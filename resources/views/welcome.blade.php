<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ContratoPro — Contratos profesionales en segundos</title>
    <meta name="description" content="Genera, personaliza y firma contratos legales con inteligencia artificial. Sin abogados, sin esperas, sin complicaciones.">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================
           DESIGN SYSTEM — ContratoPro Landing
           Dark Corporate + Lime Accent Aesthetic
        ============================================ */

        :root {
            --lime: #a3e635;
            --lime-bright: #bef264;
            --lime-dim: #84cc16;
            --bg-base: #060a14;
            --bg-card: #0d1323;
            --bg-card-hover: #111d30;
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-lime: rgba(163, 230, 53, 0.2);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: var(--bg-base);
            color: #cbd5e1;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ---- Utility: Display Font ---- */
        .font-display { font-family: 'Syne', sans-serif; }

        /* ---- Dot grid bg ---- */
        .dot-bg {
            background-image: radial-gradient(rgba(163, 230, 53, 0.07) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        /* ---- Nav ---- */
        .nav-glass {
            background: rgba(6, 10, 20, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
        }

        /* ---- Hero glow ---- */
        .hero-glow {
            background:
                radial-gradient(ellipse 70% 55% at 50% -5%, rgba(163, 230, 53, 0.13) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(56, 189, 248, 0.04) 0%, transparent 60%);
        }

        /* ---- Glowing text ---- */
        .text-glow {
            text-shadow: 0 0 50px rgba(163, 230, 53, 0.45), 0 0 100px rgba(163, 230, 53, 0.15);
        }

        /* ---- Badge pill ---- */
        .badge {
            background: linear-gradient(90deg, rgba(163, 230, 53, 0.1), rgba(163, 230, 53, 0.05));
            border: 1px solid rgba(163, 230, 53, 0.22);
        }

        /* ---- Primary button ---- */
        .btn-primary {
            background: linear-gradient(135deg, #a3e635 0%, #84cc16 100%);
            color: #0a160a;
            font-weight: 700;
            transition: all 0.2s ease;
            font-family: 'Syne', sans-serif;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #bef264 0%, #a3e635 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(163, 230, 53, 0.3), 0 2px 8px rgba(163, 230, 53, 0.2);
        }

        /* ---- Outline button ---- */
        .btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #94a3b8;
            transition: all 0.2s ease;
            font-family: 'Syne', sans-serif;
        }
        .btn-outline:hover {
            border-color: rgba(255, 255, 255, 0.35);
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.04);
        }

        /* ---- Feature card ---- */
        .feat-card {
            background: linear-gradient(145deg, rgba(13, 19, 35, 0.9), rgba(10, 14, 28, 0.6));
            border: 1px solid rgba(163, 230, 53, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .feat-card:hover {
            border-color: rgba(163, 230, 53, 0.28);
            background: linear-gradient(145deg, rgba(17, 26, 46, 0.95), rgba(13, 19, 35, 0.8));
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(163, 230, 53, 0.08);
        }

        /* ---- Icon badge ---- */
        .icon-wrap {
            background: linear-gradient(135deg, rgba(163, 230, 53, 0.15) 0%, rgba(163, 230, 53, 0.04) 100%);
            border: 1px solid rgba(163, 230, 53, 0.18);
        }

        /* ---- Step badge ---- */
        .step-badge {
            background: linear-gradient(135deg, rgba(163, 230, 53, 0.15), rgba(163, 230, 53, 0.04));
            border: 1px solid rgba(163, 230, 53, 0.25);
        }

        /* ---- Section divider ---- */
        .line-accent {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(163, 230, 53, 0.35) 50%, transparent 100%);
        }

        /* ---- Mockup card ---- */
        .mockup-card {
            background: linear-gradient(160deg, #0d1628 0%, #0a1020 100%);
            border: 1px solid rgba(163, 230, 53, 0.12);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 40px 80px rgba(0, 0, 0, 0.6),
                0 0 100px rgba(163, 230, 53, 0.04);
        }

        /* ---- Pricing cards ---- */
        .pricing-card {
            background: linear-gradient(160deg, rgba(13, 19, 35, 0.95), rgba(10, 14, 28, 0.7));
            border: 1px solid rgba(255, 255, 255, 0.07);
            transition: border-color 0.3s ease, transform 0.3s ease;
        }
        .pricing-card:hover { border-color: rgba(163, 230, 53, 0.2); transform: translateY(-2px); }

        .pricing-featured {
            background: linear-gradient(160deg, rgba(20, 32, 56, 0.95), rgba(13, 22, 40, 0.9));
            border: 1px solid rgba(163, 230, 53, 0.28);
            box-shadow: 0 0 60px rgba(163, 230, 53, 0.06), inset 0 1px 0 rgba(163, 230, 53, 0.08);
        }

        /* ---- CTA section ---- */
        .cta-section {
            background:
                radial-gradient(ellipse 60% 70% at 50% 50%, rgba(163, 230, 53, 0.07) 0%, transparent 70%);
        }

        /* ---- Animations ---- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        @keyframes pulse-lime {
            0%, 100% { box-shadow: 0 0 0 0 rgba(163, 230, 53, 0.3); }
            50%       { box-shadow: 0 0 0 6px rgba(163, 230, 53, 0); }
        }

        .fade-up  { animation: fadeUp 0.7s cubic-bezier(0.4, 0, 0.2, 1) forwards; opacity: 0; }
        .d1 { animation-delay: 0.05s; }
        .d2 { animation-delay: 0.15s; }
        .d3 { animation-delay: 0.25s; }
        .d4 { animation-delay: 0.38s; }
        .d5 { animation-delay: 0.52s; }
        .float-anim { animation: float 6s ease-in-out infinite; }

        .dot-live {
            animation: pulse-lime 2s ease-in-out infinite;
        }

        /* ---- Stat counter ---- */
        .stat-item {
            border-right: 1px solid var(--border-subtle);
        }
        .stat-item:last-child { border-right: none; }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: rgba(163, 230, 53, 0.2); border-radius: 3px; }

        /* ---- Responsive helpers ---- */
        @media (max-width: 768px) {
            .stat-item { border-right: none; border-bottom: 1px solid var(--border-subtle); }
            .stat-item:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>

<!-- ==========================================
     NAVBAR
========================================== -->
<nav class="nav-glass fixed top-0 left-0 right-0 z-50 py-4">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #a3e635, #65a30d);">
                <svg class="w-4.5 h-4.5" style="width:18px;height:18px;" fill="#0a160a" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="font-display font-bold text-white text-xl tracking-tight">ContratoPro</span>
        </a>

        <!-- Nav Links -->
        <div class="hidden lg:flex items-center gap-8 text-sm text-slate-400">
            <a href="#features"    class="hover:text-white transition-colors duration-200">Características</a>
            <a href="#how-it-works" class="hover:text-white transition-colors duration-200">Cómo funciona</a>
            <a href="#pricing"     class="hover:text-white transition-colors duration-200">Precios</a>
        </div>

        <!-- Auth CTAs -->
        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="btn-primary px-5 py-2 rounded-lg text-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-slate-400 hover:text-white transition-colors hidden sm:inline">
                        Iniciar sesión
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="btn-primary px-5 py-2 rounded-lg text-sm">
                            Empezar gratis
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>


<!-- ==========================================
     HERO
========================================== -->
<section class="hero-glow dot-bg relative pt-36 pb-20 px-6 overflow-hidden">

    <!-- Decorative blobs -->
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full opacity-10 pointer-events-none"
         style="background: radial-gradient(circle, #a3e635, transparent 70%); filter: blur(60px);"></div>
    <div class="absolute top-1/2 -right-40 w-80 h-80 rounded-full opacity-5 pointer-events-none"
         style="background: radial-gradient(circle, #38bdf8, transparent 70%); filter: blur(80px);"></div>

    <div class="max-w-6xl mx-auto">
        <div class="text-center max-w-4xl mx-auto">

            <!-- Announcement badge -->
            <div class="inline-flex items-center gap-2 badge px-4 py-2 rounded-full mb-8 fade-up d1">
                <span class="w-2 h-2 rounded-full bg-lime-400 dot-live inline-block flex-shrink-0"></span>
                <span class="text-lime-400 font-semibold text-sm">Nuevo</span>
                <span class="text-slate-500 text-sm">→ Firma digital con validez legal disponible</span>
            </div>

            <!-- Headline -->
            <h1 class="font-display font-extrabold text-5xl md:text-6xl lg:text-7xl leading-[1.05] tracking-tight text-white mb-6 fade-up d2">
                Contratos legales<br>
                <span class="text-lime-400 text-glow">en segundos.</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-xl md:text-2xl text-slate-400 leading-relaxed mb-10 max-w-2xl mx-auto font-light fade-up d3">
                Genera, personaliza y firma contratos profesionales con IA.
                Sin abogados, sin esperas, sin complicaciones.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16 fade-up d4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="btn-primary w-full sm:w-auto px-8 py-3.5 rounded-xl text-base text-center">
                        Crear mi primer contrato →
                    </a>
                @endif
                <a href="#how-it-works"
                   class="btn-outline w-full sm:w-auto px-8 py-3.5 rounded-xl text-base text-center">
                    Ver cómo funciona
                </a>
            </div>

            <!-- Trust indicators -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 fade-up d5">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-lime-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Sin tarjeta de crédito
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-lime-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Legalmente válido
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-lime-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    +5,000 empresas activas
                </span>
            </div>
        </div>

        <!-- UI Mockup -->
        <div class="mt-20 float-anim fade-up d5">
            <div class="mockup-card rounded-2xl p-1 max-w-3xl mx-auto">
                <!-- Window chrome -->
                <div class="rounded-xl overflow-hidden" style="background: #080e1c;">
                    <div class="flex items-center gap-3 px-5 py-3 border-b" style="border-color: rgba(255,255,255,0.05);">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #ff5f57;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #fec02e;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #28c840;"></div>
                        </div>
                        <div class="flex-1 rounded-md flex items-center px-3 h-6 text-xs text-slate-600"
                             style="background: rgba(255,255,255,0.04);">
                            contratopro.app/editor
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-lime-400 dot-live"></div>
                            <span class="text-xs text-lime-500">Guardado</span>
                        </div>
                    </div>

                    <div class="flex" style="min-height: 280px;">
                        <!-- Sidebar -->
                        <div class="w-48 border-r p-3 space-y-1.5 flex-shrink-0" style="border-color: rgba(255,255,255,0.05); background: rgba(255,255,255,0.01);">
                            <p class="text-xs text-slate-600 uppercase tracking-widest px-2 mb-2 font-semibold">Plantillas</p>
                            <div class="px-3 py-2 rounded-lg text-xs font-semibold"
                                 style="background: rgba(163, 230, 53, 0.12); color: #a3e635; border: 1px solid rgba(163, 230, 53, 0.2);">
                                Contrato de Servicio
                            </div>
                            @foreach(['Acuerdo NDA', 'Contrato Laboral', 'Arrendamiento', 'Freelance', 'Compraventa'] as $tpl)
                            <div class="px-3 py-2 rounded-lg text-xs text-slate-500 hover:text-slate-300 cursor-pointer transition-colors"
                                 style="background: rgba(255,255,255,0.02);">
                                {{ $tpl }}
                            </div>
                            @endforeach
                        </div>

                        <!-- Main area -->
                        <div class="flex-1 p-5 space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-slate-600 mb-1.5">Nombre del cliente</p>
                                    <div class="rounded-lg px-3 py-2.5 text-sm text-white font-medium"
                                         style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.07);">
                                        Empresa ABC S.A.S
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-600 mb-1.5">Vigencia</p>
                                    <div class="rounded-lg px-3 py-2.5 text-sm text-white"
                                         style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.07);">
                                        12 meses
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-slate-600 mb-1.5">Valor del contrato</p>
                                    <div class="rounded-lg px-3 py-2.5 text-sm font-bold"
                                         style="background: rgba(163, 230, 53, 0.07); border: 1px solid rgba(163, 230, 53, 0.15); color: #a3e635;">
                                        $12,000,000 COP
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-600 mb-1.5">Tipo de pago</p>
                                    <div class="rounded-lg px-3 py-2.5 text-sm text-white"
                                         style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.07);">
                                        Mensual
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-lg px-3 py-2.5 text-sm text-slate-500 italic"
                                 style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                Describe el alcance del servicio… la IA completará las cláusulas.
                            </div>
                            <button class="w-full py-3 rounded-xl text-sm font-bold font-display cursor-pointer transition-all"
                                    style="background: linear-gradient(135deg, #a3e635, #84cc16); color: #0a160a;">
                                ✦ Generar Contrato con IA →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ==========================================
     SOCIAL PROOF
========================================== -->
<div class="line-accent"></div>
<section class="py-14 px-6" style="background: rgba(255,255,255,0.01);">
    <div class="max-w-5xl mx-auto">
        <p class="text-center text-slate-600 text-xs font-semibold tracking-widest uppercase mb-10">
            Confiado por equipos en toda Latinoamérica
        </p>
        <div class="flex flex-wrap items-center justify-center gap-10 md:gap-16">
            @foreach(['NEXUM', 'LEGALIS', 'WORKCORP', 'BITFLOW', 'ZENTRA', 'CODEX'] as $brand)
                <span class="font-display font-extrabold text-lg tracking-widest text-slate-700 hover:text-slate-400 transition-colors duration-300 cursor-default select-none">
                    {{ $brand }}
                </span>
            @endforeach
        </div>
    </div>
</section>
<div class="line-accent"></div>


<!-- ==========================================
     STATS
========================================== -->
<section class="py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0" style="border-color: var(--border-subtle); border: 1px solid var(--border-subtle); border-radius: 16px; overflow: hidden; background: rgba(255,255,255,0.015);">
            @php
                $stats = [
                    ['value' => '+5,000', 'label' => 'Empresas activas'],
                    ['value' => '+120k',  'label' => 'Contratos generados'],
                    ['value' => '98%',    'label' => 'Tasa de satisfacción'],
                    ['value' => '<30s',   'label' => 'Tiempo promedio'],
                ];
            @endphp
            @foreach($stats as $i => $stat)
                <div class="flex flex-col items-center justify-center py-10 px-6 text-center" style="border-color: var(--border-subtle);">
                    <span class="font-display font-extrabold text-3xl md:text-4xl text-white mb-1">{{ $stat['value'] }}</span>
                    <span class="text-slate-500 text-sm">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ==========================================
     FEATURES
========================================== -->
<section id="features" class="py-28 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-20">
            <span class="badge inline-block px-4 py-1.5 rounded-full text-lime-400 text-xs font-bold tracking-wider uppercase mb-5">
                Características
            </span>
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-white leading-tight mb-5">
                Todo para contratar<br>con total confianza
            </h2>
            <p class="text-slate-400 text-lg max-w-xl mx-auto font-light leading-relaxed">
                Desde la generación automática hasta la firma, cubrimos cada etapa del ciclo de vida de tus contratos.
            </p>
        </div>

        @php
            $features = [
                [
                    'icon_path' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'icon_type' => 'filled',
                    'title' => 'Generación con IA',
                    'desc'  => 'Describe tu necesidad en lenguaje natural y la IA redacta un contrato completo, adaptado a tu industria y legislación.',
                ],
                [
                    'icon_path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'icon_type' => 'stroke',
                    'title' => 'Firma Electrónica',
                    'desc'  => 'Firma con validez legal en Colombia y Latam. Compatible con estándares internacionales de firma digital avanzada.',
                ],
                [
                    'icon_path' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                    'icon_type' => 'filled',
                    'title' => '+50 Plantillas Legales',
                    'desc'  => 'Biblioteca curada: servicios, NDA, laboral, arrendamiento, freelance, compraventa, sociedad y mucho más.',
                ],
                [
                    'icon_path' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                    'icon_type' => 'stroke',
                    'title' => 'Alertas de Vencimiento',
                    'desc'  => 'Nunca pierdas una fecha crítica. Notificaciones automáticas antes de que tus contratos expiren o necesiten renovación.',
                ],
                [
                    'icon_path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'icon_type' => 'stroke',
                    'title' => 'Múltiples Firmantes',
                    'desc'  => 'Flujos de aprobación con roles y permisos personalizados. Gestiona partes, testigos y representantes desde un panel.',
                ],
                [
                    'icon_path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'icon_type' => 'stroke',
                    'title' => 'Analytics en Tiempo Real',
                    'desc'  => 'Dashboard completo: contratos activos, vencimientos, ingresos contractuales y estado de firmas todo en un lugar.',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($features as $feat)
                <div class="feat-card rounded-2xl p-7">
                    <div class="icon-wrap w-12 h-12 rounded-xl flex items-center justify-center mb-5">
                        @if($feat['icon_type'] === 'filled')
                            <svg class="w-6 h-6" style="color: #a3e635;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="{{ $feat['icon_path'] }}"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6" style="color: #a3e635;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon_path'] }}"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-display font-bold text-lg text-white mb-2">{{ $feat['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed font-light">{{ $feat['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ==========================================
     HOW IT WORKS
========================================== -->
<section id="how-it-works" class="py-28 px-6" style="background: rgba(255,255,255,0.01);">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-20">
            <span class="badge inline-block px-4 py-1.5 rounded-full text-lime-400 text-xs font-bold tracking-wider uppercase mb-5">
                Proceso
            </span>
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-white leading-tight">
                De la idea al contrato firmado<br>
                <span class="text-lime-400">en 3 pasos.</span>
            </h2>
        </div>

        @php
            $steps = [
                [
                    'num'   => '01',
                    'title' => 'Elige tu plantilla',
                    'desc'  => 'Selecciona entre +50 plantillas legales o describe tu necesidad y la IA crea el contrato desde cero en segundos.',
                    'color' => 'rgba(163, 230, 53, 1)',
                ],
                [
                    'num'   => '02',
                    'title' => 'Personaliza con IA',
                    'desc'  => 'Completa los datos clave. La IA redacta automáticamente todas las cláusulas adaptadas a tu caso específico.',
                    'color' => 'rgba(163, 230, 53, 0.8)',
                ],
                [
                    'num'   => '03',
                    'title' => 'Firma y gestiona',
                    'desc'  => 'Envía para firmar, recibe notificaciones y gestiona el ciclo de vida completo de tus contratos desde un panel.',
                    'color' => 'rgba(163, 230, 53, 0.6)',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($steps as $i => $step)
                <div class="text-center relative">
                    <!-- Connector line (between cards) -->
                    @if($i < 2)
                        <div class="hidden md:block absolute top-10 left-full w-full h-px z-10"
                             style="background: linear-gradient(90deg, rgba(163,230,53,0.4), rgba(163,230,53,0.1)); transform: translateX(-24px) translateY(-50%); width: calc(100% - 48px); left: calc(100% - 24px + 24px); display: none;"></div>
                    @endif

                    <!-- Step badge -->
                    <div class="step-badge w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-7">
                        <span class="font-display font-black text-2xl" style="color: {{ $step['color'] }};">{{ $step['num'] }}</span>
                    </div>

                    <h3 class="font-display font-bold text-xl text-white mb-3">{{ $step['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed font-light max-w-xs mx-auto">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Step connector (visible version) -->
        <div class="hidden md:flex items-center justify-center mt-0 -mt-60 mb-60 pointer-events-none select-none" aria-hidden="true">
            <!-- just decorative, handled per-card above -->
        </div>
    </div>
</section>


<!-- ==========================================
     PRICING
========================================== -->
<section id="pricing" class="py-28 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-16">
            <span class="badge inline-block px-4 py-1.5 rounded-full text-lime-400 text-xs font-bold tracking-wider uppercase mb-5">
                Precios
            </span>
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-white leading-tight mb-4">
                Planes simples y transparentes
            </h2>
            <p class="text-slate-400 text-lg font-light">Sin costos ocultos. Cancela cuando quieras.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 items-start">

            <!-- Starter -->
            <div class="pricing-card rounded-2xl p-8">
                <div class="mb-6">
                    <h3 class="font-display font-bold text-xl text-white mb-1">Starter</h3>
                    <p class="text-slate-500 text-sm">Para freelancers y solopreneurs</p>
                </div>
                <div class="flex items-end gap-1 mb-7">
                    <span class="font-display font-black text-5xl text-white">$0</span>
                    <span class="text-slate-500 text-sm mb-2">/ mes</span>
                </div>
                <ul class="space-y-3 mb-8">
                    @foreach(['5 contratos/mes', '10 plantillas incluidas', 'Descarga en PDF', 'Soporte por email'] as $item)
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #a3e635;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="btn-outline block text-center py-3 rounded-xl text-sm font-semibold">
                        Comenzar gratis
                    </a>
                @endif
            </div>

            <!-- Pro (featured) -->
            <div class="pricing-featured rounded-2xl p-8 relative">
                <!-- Most popular badge -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                    <span class="badge text-xs font-bold px-4 py-1.5 rounded-full text-lime-400 whitespace-nowrap">
                        ✦ Más popular
                    </span>
                </div>
                <div class="mb-6">
                    <h3 class="font-display font-bold text-xl text-white mb-1">Pro</h3>
                    <p class="text-slate-400 text-sm">Para equipos en crecimiento</p>
                </div>
                <div class="flex items-end gap-1 mb-7">
                    <span class="font-display font-black text-5xl text-white">$29</span>
                    <span class="text-slate-400 text-sm mb-2">USD / mes</span>
                </div>
                <ul class="space-y-3 mb-8">
                    @foreach(['Contratos ilimitados', '+50 plantillas', 'Firma electrónica', 'IA avanzada', 'Múltiples firmantes', 'Soporte prioritario 24/7'] as $item)
                        <li class="flex items-center gap-3 text-sm text-slate-300">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #a3e635;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="btn-primary block text-center py-3 rounded-xl text-sm">
                        Empezar con Pro
                    </a>
                @endif
            </div>

            <!-- Enterprise -->
            <div class="pricing-card rounded-2xl p-8">
                <div class="mb-6">
                    <h3 class="font-display font-bold text-xl text-white mb-1">Enterprise</h3>
                    <p class="text-slate-500 text-sm">Para grandes organizaciones</p>
                </div>
                <div class="mb-7">
                    <span class="font-display font-bold text-2xl text-white">Personalizado</span>
                    <p class="text-slate-500 text-sm mt-1">Cotización a medida</p>
                </div>
                <ul class="space-y-3 mb-8">
                    @foreach(['Todo en Pro', 'SSO & SAML', 'API personalizada', 'SLA garantizado', 'Compliance avanzado', 'Gerente de cuenta dedicado'] as $item)
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: #a3e635;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="#"
                   class="btn-outline block text-center py-3 rounded-xl text-sm font-semibold">
                    Hablar con ventas
                </a>
            </div>

        </div>
    </div>
</section>


<!-- ==========================================
     TESTIMONIALS
========================================== -->
<div class="line-accent"></div>
<section class="py-24 px-6" style="background: rgba(255,255,255,0.01);">
    <div class="max-w-5xl mx-auto">

        <p class="text-center text-slate-500 text-xs uppercase tracking-widest font-semibold mb-14">Lo que dicen nuestros clientes</p>

        @php
            $testimonials = [
                [
                    'text'   => '"ContratoPro nos ahorró 3 días de trabajo por contrato. Ahora generamos los mismos documentos en minutos con total seguridad legal."',
                    'author' => 'Daniela Restrepo',
                    'role'   => 'CEO, Nexum Tech',
                    'avatar' => 'DR',
                ],
                [
                    'text'   => '"La firma electrónica integrada fue un game changer para nuestro equipo comercial. Cerramos contratos el mismo día."',
                    'author' => 'Sebastián Mora',
                    'role'   => 'Director Comercial, WorkCorp',
                    'avatar' => 'SM',
                ],
                [
                    'text'   => '"Excelente plataforma. Las plantillas son muy completas y el editor con IA entiende perfectamente el lenguaje legal colombiano."',
                    'author' => 'Valeria Cruz',
                    'role'   => 'Abogada, Legalis Firma',
                    'avatar' => 'VC',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-5">
            @foreach($testimonials as $t)
                <div class="feat-card rounded-2xl p-7">
                    <div class="flex mb-4">
                        @for($s = 0; $s < 5; $s++)
                            <svg class="w-4 h-4" style="color: #a3e635;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6 font-light italic">{{ $t['text'] }}</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold font-display flex-shrink-0"
                             style="background: linear-gradient(135deg, rgba(163,230,53,0.2), rgba(163,230,53,0.05)); color: #a3e635; border: 1px solid rgba(163,230,53,0.2);">
                            {{ $t['avatar'] }}
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold">{{ $t['author'] }}</p>
                            <p class="text-slate-500 text-xs">{{ $t['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<div class="line-accent"></div>


<!-- ==========================================
     FINAL CTA
========================================== -->
<section class="cta-section py-32 px-6">
    <div class="max-w-3xl mx-auto text-center">

        <h2 class="font-display font-extrabold text-5xl md:text-6xl text-white leading-[1.05] mb-6">
            ¿Listo para contratar<br>
            <span class="text-lime-400 text-glow">sin fricciones?</span>
        </h2>
        <p class="text-xl text-slate-400 mb-12 leading-relaxed font-light max-w-xl mx-auto">
            Únete a más de 5,000 empresas que ya generan contratos profesionales en segundos con ContratoPro.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="btn-primary px-10 py-4 rounded-xl text-lg text-center">
                    Crear cuenta gratis →
                </a>
            @endif
            <a href="#pricing"
               class="btn-outline px-10 py-4 rounded-xl text-lg text-center">
                Ver planes
            </a>
        </div>

        <p class="text-slate-600 text-sm">
            Sin tarjeta de crédito &nbsp;·&nbsp; Cancela cuando quieras &nbsp;·&nbsp; Soporte incluido
        </p>
    </div>
</section>


<!-- ==========================================
     FOOTER
========================================== -->
<footer style="border-top: 1px solid var(--border-subtle);" class="py-12 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg, #a3e635, #65a30d);">
                    <svg style="width:14px;height:14px;" fill="#0a160a" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="font-display font-bold text-white">ContratoPro</span>
            </a>

            <!-- Nav links -->
            <div class="flex flex-wrap gap-6 text-sm text-slate-500">
                <a href="#features"     class="hover:text-white transition-colors">Características</a>
                <a href="#how-it-works" class="hover:text-white transition-colors">Cómo funciona</a>
                <a href="#pricing"      class="hover:text-white transition-colors">Precios</a>
                <a href="#"             class="hover:text-white transition-colors">Privacidad</a>
                <a href="#"             class="hover:text-white transition-colors">Términos</a>
            </div>
        </div>

        <div class="line-accent mb-8"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-slate-600 text-sm">
            <p>© {{ date('Y') }} ContratoPro. Todos los derechos reservados.</p>
            <p>Hecho con ✦ para equipos que mueven el mundo.</p>
        </div>
    </div>
</footer>

</body>
</html>