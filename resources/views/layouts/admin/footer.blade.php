<!-- Floating WhatsApp Button -->
{{-- ============================= --}}
{{--       GLOBAL FOOTER          --}}
{{-- ============================= --}}
<footer class="admin-footer fade-in">
    <div class="container-fluid px-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

            {{-- LEFT --}}
            <div class="text-center text-md-start mb-3 mb-md-0">
                <h6 class="fw-bold mb-0 text-primary">
                    Sistem Administrasi Desa
                </h6>
                <small class="text-muted">
                    Dashboard • Data Warga • Manajemen User
                </small>
                <br>
                <small class="text-muted">
                    © <span id="year"></span> Bina Desa — All Rights Reserved
                </small>
            </div>

            {{-- RIGHT --}}
            <div class="d-flex align-items-center gap-3" style="font-size:20px;">
                <a href="#" class="text-primary" title="Website Resmi Desa">
                    <i class="fa-solid fa-globe"></i>
                </a>
                <a href="#" class="text-success" title="Kontak Admin">
                    <i class="fa-solid fa-phone"></i>
                </a>
                <a href="#" class="text-dark" title="Dokumentasi Sistem">
                    <i class="fa-solid fa-book"></i>
                </a>
            </div>

        </div>
    </div>
</footer>


{{-- DYNAMIC YEAR --}}
<script>
    document.getElementById("year").innerText = new Date().getFullYear();
</script>


<a href="https://wa.me/6289524214721" target="_blank" id="whatsapp-float">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="45"
        height="45">
</a>

<style>
    #whatsapp-float {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background-color: #076228;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        transition: all 0.3s ease-in-out;
    }

    #whatsapp-float img {
        display: block;
    }

    #whatsapp-float:hover {
        transform: scale(1.1);
        background-color: #20b955;
    }


    /* =====================
   ADMIN FOOTER FIX
===================== */
    .admin-footer {
        margin-left: 260px;
        /* 🔥 sejajar sidebar */
        background: #1fe962;
        border-top: 1px solid #e5e7eb;
        padding: 20px 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, .05);
    }

    /* responsive */
    @media (max-width: 992px) {
        .admin-footer {
            margin-left: 0;
        }
    }
</style>

{{-- EndWa --}}
