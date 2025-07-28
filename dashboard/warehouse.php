<?php include 'include/sidebar.php' ?>

<div class="main-content">
    <?php include 'include/navbar.php' ?>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: #22223b; color: #fca311;">
                        <h4 class="mb-0">RoyEdge Warehouses</h4>
                    </div>
                    <div class="card-body" style="background: #f7f7f7;">
                        <div class="alert" style="background-color: rgba(34,34,59,0.08); border-left: 4px solid #22223b; color: #22223b;">
                            <strong>Find the address and contact for each RoyEdge warehouse below. Use the copy icon to quickly copy any delivery phone number.</strong>
                        </div>
                        <div class="warehouses-list mt-4">
                            <!-- China Section -->
                            <h5 class="mt-4 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse China</h5>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Air Cargo Consolidation Warehouse (Guangzhou)</div>
                                <div class="warehouse-address text-muted">广州市白云区走马岗路2号国太商贸城B120 送货 <span class="warehouse-number" id="wh-china-air">19584794795</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-china-air" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Sea Cargo Consolidation Warehouse (Guangzhou)</div>
                                <div class="warehouse-address text-muted">广州市白云区走马岗路2号国太鞋城1楼A161档 送货 <span class="warehouse-number" id="wh-china-sea">19584753254</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-china-sea" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Air Cargo Warehouse (Guangzhou)</div>
                                <div class="warehouse-address text-muted">广州市越秀区广园西路218号盈富商贸城A2区 A2015-A2017 送货 <span class="warehouse-number" id="wh-china-air2">13922367386</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-china-air2" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Sea Cargo Warehouse (Foshan)</div>
                                <div class="warehouse-address text-muted">广东省佛山市南海区里水镇里广路胜利社区胜利工业区60号A栋88仓. 送货 <span class="warehouse-number" id="wh-foshan">15989233089</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-foshan" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Sea Cargo Warehouse (Yiwu)</div>
                                <div class="warehouse-address text-muted">义乌市城西街道何泮山村美卡佛一楼1号仓. 送货 <span class="warehouse-number" id="wh-yiwu">18358982804</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-yiwu" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">Sea Cargo Warehouse (PURELY BATTERY/DANGEROUS GOODS ONLY) [Foshan II]</div>
                                <div class="warehouse-address text-muted">广东省佛山市南海区里水镇里广路72号（外国人管理中心门口进入一直开车到最后，导航:利芭堤仓库，收货时间：周一至周日 早9点-晚8点) 送货 <span class="warehouse-number" id="wh-foshan2">13640815971</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-foshan2" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <!-- Dubai Section -->
                            <h5 class="mt-5 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse Dubai</h5>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge Dubai Address</div>
                                <div class="warehouse-address text-muted">مبنى بن ثاني، خلف فندق سكاي بارك، ديرة، دبي. التوصيل: <span class="warehouse-number" id="wh-dubai">+971552326507</span>
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-dubai" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <!-- USA Section -->
                            <h5 class="mt-5 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse USA</h5>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge USA Address - New Jersey</div>
                                <div class="warehouse-address text-muted">New Jersey, USA 1825 Pennsylvania Avenue Linden, NJ. 07036 (Delivery: <span class="warehouse-number" id="wh-usa">+1 908 275 3675</span>)
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-usa" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <!-- UK Section -->
                            <h5 class="mt-5 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse UK</h5>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge UK Address</div>
                                <div class="warehouse-address text-muted">UNIT 1, Loughborough Center, 105 Angell Road, Brixton, London. Zipcode: SW9 7PD</div>
                            </div>
                            <!-- Vietnam Section -->
                            <h5 class="mt-5 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse Vietnam</h5>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge Vietnam Address - Ho Chi Minh</div>
                                <div class="warehouse-address text-muted">141 Phan Huy Ích P.15,Q. Tân Bình, Hcmc (vận chuyển: <span class="warehouse-number" id="wh-vn-hcmc">+84793295702</span>)
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-vn-hcmc" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <div class="warehouse-item mb-3 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge Vietnam Address - Hanoi</div>
                                <div class="warehouse-address text-muted">226 Võ Chí Công, Tây Hồ, Hanoi. (vận chuyển: <span class="warehouse-number" id="wh-vn-hanoi">+84914790022</span>)
                                    <button class="btn btn-light copy-btn ms-2" data-copy-target="wh-vn-hanoi" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.1rem;"></i></button>
                                </div>
                            </div>
                            <!-- Germany Section -->
                            <h5 class="mt-5 mb-3" style="color: #22223b; font-weight: 700;">RoyEdge Warehouse Germany</h5>
                            <div class="warehouse-item mb-2 p-3 rounded" style="background: #fff; border-left: 4px solid #22223b;">
                                <div class="warehouse-title" style="font-weight: 600; color: #22223b;">RoyEdge Germany Address</div>
                                <div class="warehouse-address text-muted">Nordstrasse 5, 99427 Weimar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Add this script at the bottom of your PHP file, before the closing </body> tag

    document.addEventListener('DOMContentLoaded', function() {
        // Function to show copy feedback
        function showCopyFeedback(btn) {
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ri-check-line" style="color: #28a745; font-size: 1.3rem;"></i>';
            btn.style.background = '#d4edda';

            setTimeout(function() {
                btn.innerHTML = originalIcon;
                btn.style.background = '#fff';
            }, 1500);
        }

        // Event listener for copy buttons
        document.body.addEventListener('click', function(e) {
            // Check if clicked element is a copy button or its child
            const copyBtn = e.target.closest('.copy-btn');
            if (copyBtn) {
                e.preventDefault(); // Prevent any default behavior

                const targetId = copyBtn.getAttribute('data-copy-target');
                const numberElem = document.getElementById(targetId);

                if (numberElem) {
                    const number = numberElem.textContent.trim();

                    // Modern clipboard API
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(number).then(function() {
                            showCopyFeedback(copyBtn);
                            console.log('Number copied:', number); // For debugging
                        }).catch(function(err) {
                            console.error('Failed to copy:', err);
                            fallbackCopy(number, copyBtn);
                        });
                    } else {
                        // Fallback for older browsers or non-HTTPS
                        fallbackCopy(number, copyBtn);
                    }
                } else {
                    console.error('Target element not found:', targetId);
                }
            }
        });

        // Fallback copy function
        function fallbackCopy(text, btn) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopyFeedback(btn);
                    console.log('Number copied (fallback):', text);
                } else {
                    console.error('Copy command failed');
                }
            } catch (err) {
                console.error('Copy failed:', err);
            }

            document.body.removeChild(textarea);
        }
    });
</script>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<style>
    .warehouses-list .warehouse-item {
        transition: box-shadow 0.2s;
    }

    .warehouses-list .warehouse-item:hover {
        box-shadow: 0 2px 12px rgba(34, 34, 59, 0.08);
    }

    .copy-btn {
        border: none;
        background: #fff;
        box-shadow: 0 1px 4px rgba(34, 34, 59, 0.04);
        border-radius: 6px;
        transition: background 0.2s;
    }

    .copy-btn:hover {
        background: #fca31122;
    }
</style>
</body>

</html>