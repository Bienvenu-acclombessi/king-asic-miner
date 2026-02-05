<style>
    .team-container{
        
    }
        .team-member-col {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 0 0 auto;
            width: 120px; /* Largeur par défaut - juste l'image */
            height: 200px;
        }

        .team-member-card {
            cursor: pointer;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .team-member-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        .team-member-content {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .team-member-image {
            flex-shrink: 0;
            width: 120px;
            height: 100%;
            overflow: hidden;
        }

        .team-member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .team-member-details {
            flex: 1;
            opacity: 0;
            width: 0;
            overflow: hidden;
            padding: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* État actif (première carte par défaut ou au survol) */
        .team-member-col.active {
            width: 450px;
        }

        .team-member-col.active .team-member-details {
            opacity: 1;
            width: 330px;
            padding: 20px;
        }

        /* Survol */
        .team-member-col:hover {
            width: 450px;
        }

        .team-member-col:hover .team-member-details {
            opacity: 1;
            width: 330px;
            padding: 20px;
        }

        .team-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        .social-links a {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            min-width: 40px;
        }

        .social-links i {
            font-size: 16px;
        }

        h5 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .text-primary {
            color: #0d6efd !important;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .team-member-col,
            .team-member-col.active,
            .team-member-col:hover {
                width: 100%;
            }

            .team-member-image {
                width: 100px;
                height: 100px;
            }

            .team-member-col.active .team-member-details,
            .team-member-col:hover .team-member-details {
                width: calc(100% - 100px);
                padding: 15px;
            }

            .team-member-details h5 {
                font-size: 16px;
            }
            
            .team-member-details p {
                font-size: 12px;
            }

            .team-row {
                flex-wrap: wrap;
            }
        }
    </style>


    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Meet Our Team</h2>
                    <p class="text-muted mt-3 mx-auto" style="max-width: 700px;">
                        Users verify and confirm Apexto's sales team. Our dedicated professionals are committed to providing you with expert guidance and exceptional service in cryptocurrency mining and ASIC hardware solutions.
                    </p>
                </div>
            </div>

            <!-- Première rangée -->
            <div class="team-row mb-4" data-row="1">
                <div class="team-member-col active">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Wendy-Huang.jpg" alt="Wendy Huang">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Wendy Huang</h5>
                                <p class="text-primary mb-2">Salesperson</p>
                                <p class="text-muted mb-3">Since 2019, helped thousands of clients from different countries start their mining journey.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567890" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/wendyhuang" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:wendy@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Jessica-Yin_.jpg" alt="Jessica Yin">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Jessica Yin</h5>
                                <p class="text-primary mb-2">Technical Advisor</p>
                                <p class="text-muted mb-3">Expert in ASIC hardware optimization with over 8 years of experience in cryptocurrency mining.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567891" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/jessicayin" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:jessica@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Lisa-Chen_.jpg" alt="Lisa Chen">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Lisa Chen</h5>
                                <p class="text-primary mb-2">Sales Manager</p>
                                <p class="text-muted mb-3">Specialized in enterprise mining solutions and large-scale deployment strategies.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567892" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/lisachen" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:lisa@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Kevin-Duan_.jpg" alt="Kevin Duan">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Kevin Duan</h5>
                                <p class="text-primary mb-2">Customer Support</p>
                                <p class="text-muted mb-3">Dedicated to ensuring smooth operations and providing 24/7 technical assistance.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567893" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/kevinduan" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:kevin@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Faye-Zhang_.jpg" alt="Faye Zhang">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Faye Zhang</h5>
                                <p class="text-primary mb-2">Business Development</p>
                                <p class="text-muted mb-3">Building partnerships and expanding mining opportunities across global markets.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567894" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/fayezhang" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:faye@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Bella-Guo_.jpg" alt="Bella Guo">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Bella Guo</h5>
                                <p class="text-primary mb-2">Product Specialist</p>
                                <p class="text-muted mb-3">Expert knowledge of all ASIC models and mining equipment specifications.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567895" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/bellaguo" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:bella@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième rangée -->
            <div class="team-row" data-row="2">
                <div class="team-member-col active">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Joy.jpg" alt="Joy">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Joy</h5>
                                <p class="text-primary mb-2">Mining Consultant</p>
                                <p class="text-muted mb-3">Providing strategic advice for maximizing mining profitability and efficiency.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567896" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/joy" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:joy@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Mavis-Bao_.jpg" alt="Mavis Bao">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Mavis Bao</h5>
                                <p class="text-primary mb-2">Operations Manager</p>
                                <p class="text-muted mb-3">Overseeing logistics and ensuring timely delivery of mining equipment worldwide.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567897" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/mavisbao" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:mavis@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Selina-Xu_.jpg" alt="Selina Xu">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Selina Xu</h5>
                                <p class="text-primary mb-2">Account Manager</p>
                                <p class="text-muted mb-3">Managing client relationships and providing personalized mining solutions.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567898" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/selinaxu" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:selina@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/Rena-Zeng.jpg" alt="Rena Zeng">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Rena Zeng</h5>
                                <p class="text-primary mb-2">Technical Engineer</p>
                                <p class="text-muted mb-3">Specialized in hardware troubleshooting and mining rig optimization.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567899" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/renazeng" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:rena@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/fiona Hu.jpg" alt="Fiona Hu">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Fiona Hu</h5>
                                <p class="text-primary mb-2">Sales Executive</p>
                                <p class="text-muted mb-3">Helping clients choose the right mining equipment for their specific needs.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567800" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/fionahu" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:fiona@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-member-col">
                    <div class="team-member-card">
                        <div class="team-member-content">
                            <div class="team-member-image">
                                <img src="assets/kingshop/assets/images/team/owen-Ou.jpg" alt="Owen Ou">
                            </div>
                            <div class="team-member-details">
                                <h5 class="mb-1">Owen Ou</h5>
                                <p class="text-primary mb-2">Market Analyst</p>
                                <p class="text-muted mb-3">Tracking cryptocurrency trends and advising on optimal mining strategies.</p>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://wa.me/1234567801" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://t.me/owenou" target="_blank" class="btn btn-sm btn-outline-info" title="Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="mailto:owen@apexto.com" class="btn btn-sm btn-outline-secondary" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const teamRows = document.querySelectorAll('.team-row');
            
            teamRows.forEach(row => {
                const cols = row.querySelectorAll('.team-member-col');
                
                cols.forEach(col => {
                    col.addEventListener('mouseenter', function() {
                        // Retirer la classe active de toutes les cartes de cette rangée
                        cols.forEach(c => c.classList.remove('active'));
                        // Ajouter la classe active à la carte survolée
                        this.classList.add('active');
                    });
                });
            });
        });
    </script>
