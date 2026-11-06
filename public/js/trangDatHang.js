let debounceTimer;

function formatVnd(n) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
}

function tinhKhoangCachKm(lat1, lon1, lat2, lon2) {
    const R = 6371; 
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

const boDau = s => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd');

const tinhPhiKm = km => km <= 5 ? 0 : km <= 10 ? 12000 : km <= 15 ? 20000 : km <= 30 ? 30000 : km <= 50 ? 40000 : 50000;

function tinhPhiText(addr) {
    const c = boDau(addr);
    const map = [
        [0, ['co nhue', 'duc thang', 'tang thiet giap', 'thuy phuong', 'xuan dinh', 'bac tu liem', 'mo dia chat']],
        [12000, ['cau giay', 'nam tu liem', 'tay ho']],
        [20000, ['ba dinh', 'dong da', 'thanh xuan', 'hoan kiem', 'hai ba trung']],
        [30000, ['hoang mai', 'ha dong', 'long bien', 'hoai duc', 'dan phuong', 'dong anh', 'thanh tri', 'me linh', 'gia lam']],
        [40000, ['soc son', 'thach that', 'quoc oai', 'chuong my', 'son tay', 'ba vi', 'thuong tin']]
    ];
    for (const [fee, places] of map) {
        if (places.some(p => c.includes(p))) return fee;
    }
    return (c.includes('ha noi') || c.includes('hanoi')) ? 25000 : 40000;
}

function capNhatPhiShipTuToaDo(lat, lng) {
    const addr = (document.getElementById('address')?.value || '').trim();
    let fee = 0, note = '';

    if (window.TONG_TIEN_HANG >= 300000) {
        fee = 0; note = 'Miễn phí giao hàng (Freeship)';
    } else if (lat && lng) {
        const km = tinhKhoangCachKm(window.SHOP_LAT, window.SHOP_LNG, lat, lng);
        fee = tinhPhiKm(km);
        note = fee === 0 ? `Miễn phí giao hàng (Cách shop ${km.toFixed(1)}km)` : `${formatVnd(fee)} (Cách shop ${km.toFixed(1)}km)`;
    } else {
        fee = addr.length >= 2 ? tinhPhiText(addr) : 0;
        note = fee === 0 ? 'Miễn phí giao hàng (Gần shop)' : formatVnd(fee);
    }

    window.currentPhiShip = fee;
    const elInput = document.getElementById('phi_van_chuyen_input');
    if (elInput) elInput.value = fee;

    const elShip = document.getElementById('hienThiPhiShip');
    if (elShip) {
        elShip.innerHTML = note;
        elShip.style.color = fee === 0 ? '#16a34a' : '#374151';
        elShip.style.fontWeight = fee === 0 ? 'bold' : 'normal';
    }
    if (document.getElementById('displayPhiShip')) document.getElementById('displayPhiShip').textContent = note;

    tinhLaiTongThanhToan();
}

function tinhLaiTongThanhToan() {
    const tienGiam = parseFloat(document.getElementById('tien_giam')?.value) || 0;
    const tongCuoi = Math.max(0, window.TONG_TIEN_HANG - tienGiam) + window.currentPhiShip;

    const elTongThanhToan = document.getElementById('hienThiTongThanhToan');
    if (elTongThanhToan) elTongThanhToan.textContent = formatVnd(tongCuoi);

    const elInputTongTien = document.getElementById('inputTongTien');
    if (elInputTongTien) elInputTongTien.value = tongCuoi;

    if (document.getElementById('displayTongSauGiam')) {
        document.getElementById('displayTongSauGiam').textContent = formatVnd(tongCuoi);
    }

    capNhatQrCode(tongCuoi);
}

function toggleQR(show) {
    const qrArea = document.getElementById('qr_code_area');
    if (qrArea) qrArea.style.display = show ? "block" : "none";
}

function capNhatQrCode(soTien) {
    const imgQr = document.getElementById('imgQrCode');
    if (imgQr) {
        const newQrUrl = `https://img.vietqr.io/image/${window.BANK_ID}-${window.ACCOUNT_NO}-compact2.png?amount=${soTien}&addInfo=${window.MA_CHUYEN_KHOAN}&accountName=${window.ACCOUNT_NAME}`;
        imgQr.src = newQrUrl;
    }
}

// --- 2. XỬ LÝ GỢI Ý ĐỊA CHỈ ---
async function goiYDiaChi(query) {
    clearTimeout(debounceTimer);
    const box = document.getElementById('addressSuggestions');
    if (!query || query.trim().length < 2) {
        if (box) box.style.display = 'none';
        capNhatPhiShipTuToaDo(null, null);
        return;
    }

    capNhatPhiShipTuToaDo(null, null);

    debounceTimer = setTimeout(async () => {
        const cleanQuery = query.trim();
        let items = [];

        try {
            const photonRes = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(cleanQuery)}&limit=5`);
            if (photonRes.ok) {
                const photonData = await photonRes.json();
                if (photonData && photonData.features && photonData.features.length > 0) {
                    items = photonData.features.map(f => {
                        const p = f.properties || {};
                        const parts = [];
                        if (p.name) parts.push(p.name);
                        if (p.street && p.street !== p.name) parts.push(p.street);
                        if (p.district) parts.push(p.district);
                        if (p.city && p.city !== p.name) parts.push(p.city);
                        if (p.country) parts.push(p.country);
                        const displayName = parts.length > 0 ? parts.join(', ') : (p.name || cleanQuery);
                        return {
                            name: displayName,
                            lat: f.geometry.coordinates[1],
                            lon: f.geometry.coordinates[0]
                        };
                    });
                }
            }
        } catch (err) {}

        if (items.length === 0) {
            try {
                const nomRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(cleanQuery)}&countrycodes=vn&limit=5`);
                if (nomRes.ok) {
                    const nomData = await nomRes.json();
                    if (nomData && nomData.length > 0) {
                        items = nomData.map(item => ({
                            name: item.display_name,
                            lat: parseFloat(item.lat),
                            lon: parseFloat(item.lon)
                        }));
                    }
                }
            } catch (e) {}
        }

        if (!box) return;
        box.innerHTML = '';

        if (items.length === 0) {
            box.style.display = 'none';
            return;
        }

        items.forEach(item => {
            const div = document.createElement('div');
            div.textContent = item.name;
            div.onclick = function() {
                document.getElementById('address').value = item.name;
                if (document.getElementById('latitude')) document.getElementById('latitude').value = parseFloat(item.lat).toFixed(8);
                if (document.getElementById('longitude')) document.getElementById('longitude').value = parseFloat(item.lon).toFixed(8);
                box.style.display = 'none';
                capNhatPhiShipTuToaDo(parseFloat(item.lat), parseFloat(item.lon));
            };
            box.appendChild(div);
        });
        box.style.display = 'block';
    }, 350);
}

// --- 3. XỬ LÝ CHECKOUT & THÔNG TIN ---
function checkInfoBeforePayment() {
    let isValid = true;
    ['name', 'sdt', 'email', 'address'].forEach(id => {
        let el = document.getElementById(id);
        let err = document.getElementById(id + 'Error');

        if (el && el.value.trim() === "") { 
            if (err) err.style.display = "block"; 
            el.style.borderColor = "red"; 
            el.style.borderWidth = "1px"; 
            isValid = false; 
        } else if (el) { 
            if (err) err.style.display = "none"; 
            el.style.borderColor = "#ddd"; 
            el.style.borderWidth = "1px"; 
        }
    });

    if (isValid) {
        const paymentSec = document.getElementById('payment_section');
        const btnCont = document.getElementById('btnContinue');
        if (paymentSec) paymentSec.style.display = "block";
        if (btnCont) btnCont.style.display = "none";
        if (paymentSec) paymentSec.scrollIntoView({ behavior: 'smooth' });
    } 
}

// --- 4. XỬ LÝ VOUCHER KHUYẾN MÃI & POPUP ---
async function apDungVoucher() {
        const inputVoucher = document.getElementById('ma_voucher_input');
        const ma = inputVoucher ? inputVoucher.value.trim() : '';
        const msg = document.getElementById('voucherMsg');
        const btnApDung = document.getElementById('btnApDungVoucher');

        if (!ma) { 
            if (msg) {
                msg.style.color = '#dc2626'; 
                msg.style.display = 'block'; 
                msg.textContent = 'Vui lòng nhập mã khuyến mãi.'; 
            }
            return; 
        }

        if (msg) {
            msg.style.display = 'block';
            msg.style.color = '#6366f1';
            msg.textContent = 'Đang kiểm tra...';
        }
        if (btnApDung) btnApDung.disabled = true;

        try {
        const res = await fetch(window.voucherApplyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ ma_khuyen_mai: ma, tong_tien: TONG_TIEN_HANG })
        });

        const data = await res.json();

            if (data.success) {
                if (msg) {
                    msg.style.color = '#059669';
                    msg.textContent = '✅ ' + data.message;
                }

                if (document.getElementById('tongGoc')) 
                    document.getElementById('tongGoc').style.display = 'none';
                if (document.getElementById('chiTietGia')) 
                    document.getElementById('chiTietGia').style.display = 'block';
                if (document.getElementById('displayTongGoc')) 
                    document.getElementById('displayTongGoc').textContent = formatVnd(TONG_TIEN_HANG);
                if (document.getElementById('displayTienGiam')) 
                    document.getElementById('displayTienGiam').textContent = '- ' + formatVnd(data.tien_giam);

                if (document.getElementById('ma_khuyen_mai')) 
                    document.getElementById('ma_khuyen_mai').value = ma;
                if (document.getElementById('tien_giam')) 
                    document.getElementById('tien_giam').value = data.tien_giam;

                tinhLaiTongThanhToan();

                if (document.getElementById('btnXoaVoucher')) document.getElementById('btnXoaVoucher').style.display = 'inline-block';
                if (inputVoucher) inputVoucher.disabled = true;
                if (btnApDung) btnApDung.style.display = 'none';
            } else {
                if (msg) {
                    msg.style.color = '#dc2626';
                    msg.textContent = '❌ ' + data.message;
                }
                xoaVoucher(false);
            }
        } catch(e) {
            if (msg) {
                msg.style.color = '#dc2626';
                msg.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            }
        } finally {
            if (btnApDung) btnApDung.disabled = false;
        }
    }


function xoaVoucher(showMsg = true) {
    const inputVoucher = document.getElementById('ma_voucher_input');
    if (inputVoucher) {
        inputVoucher.value = '';
        inputVoucher.disabled = false;
    }
    if (document.getElementById('ma_khuyen_mai')) document.getElementById('ma_khuyen_mai').value = '';
    if (document.getElementById('tien_giam')) document.getElementById('tien_giam').value = '0';

    if (document.getElementById('tongGoc')) document.getElementById('tongGoc').style.display = 'block';
    if (document.getElementById('chiTietGia')) document.getElementById('chiTietGia').style.display = 'none';
    if (document.getElementById('btnXoaVoucher')) document.getElementById('btnXoaVoucher').style.display = 'none';
    if (document.getElementById('btnApDungVoucher')) document.getElementById('btnApDungVoucher').style.display = 'inline-block';

    tinhLaiTongThanhToan();

    const msg = document.getElementById('voucherMsg');
    if (msg && showMsg) { msg.style.display = 'none'; }
}

async function moPopupVoucher() {
    const overlay = document.getElementById('popupVoucherOverlay');
    const popup   = document.getElementById('popupVoucher');
    const loading = document.getElementById('popupVoucherLoading');
    const list    = document.getElementById('popupVoucherList');
    const empty   = document.getElementById('popupVoucherEmpty');

    if (!overlay || !popup) return;

    overlay.style.display = 'block';
    popup.style.display   = 'flex';
    loading.style.display = 'block';
    list.style.display    = 'none';
    empty.style.display   = 'none';
    list.innerHTML        = '';

    try {
        const res  = await fetch(window.voucherListUrl);
        const data = await res.json();

        loading.style.display = 'none';

        if (!data.success || data.data.length === 0) {
            empty.style.display = 'block';
            return;
        }

        list.style.display = 'block';

        const sorted = [...data.data].sort((a, b) => {
            if (a.da_dung_boi_ban === b.da_dung_boi_ban) {
                return 0;
            }
            return a.da_dung_boi_ban ? 1 : -1;
        });

        sorted.forEach(km => {
            const disabled   = km.da_dung_boi_ban;
            const isPercent  = km.loai_giam === 'phan_tram';

            // Màu sắc: xám nếu đã dùng, màu theo loại nếu chưa
            const bgColor  = disabled ? '#f8fafc' : (isPercent ? '#f0fdf4' : '#eff6ff');
            const bdColor  = disabled ? '#e2e8f0' : (isPercent ? '#bbf7d0' : '#bfdbfe');
            const tagColor = disabled ? '#94a3b8' : (isPercent ? '#059669' : '#2563eb');
            const tagBg    = disabled ? '#f1f5f9' : (isPercent ? '#dcfce7' : '#dbeafe');

            const minDon = km.don_hang_toi_thieu > 0
                ? `<span style="font-size:11px; color:#6b7280;">🛒 Đơn từ ${new Intl.NumberFormat('vi-VN').format(km.don_hang_toi_thieu)}đ</span>`
                : '';

            const topRightBadge = disabled
                ? `<span style="font-size:11px; background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:99px; font-weight:600; border:1px solid #e2e8f0;">✓ Đã dùng</span>`
                : (km.con_lai_label
                    ? `<span style="font-size:11px; background:#fef2f2; color:#dc2626; padding:2px 7px; border-radius:99px; font-weight:600;">${km.con_lai_label}</span>`
                    : '');

            const actionLabel = disabled
                ? `<span style="font-size:12px; color:#94a3b8; white-space:nowrap;">Đã sử dụng</span>`
                : `<span style="font-size:12px; font-weight:600; color:${tagColor}; white-space:nowrap;">Dùng ngay →</span>`;

            const card = document.createElement('div');
            card.style.cssText = `margin-bottom:12px; border:1px solid ${bdColor}; border-radius:12px; background:${bgColor}; overflow:hidden; transition:box-shadow 0.2s, transform 0.1s;` + (disabled ? ' opacity:0.65;' : ' cursor:pointer;');

            if (!disabled) {
                card.onmouseenter = () => { card.style.boxShadow = '0 4px 16px rgba(99,102,241,0.15)'; card.style.transform = 'translateY(-1px)'; };
                card.onmouseleave = () => { card.style.boxShadow = 'none'; card.style.transform = 'none'; };
                card.onclick = () => chonVoucher(km.ma_khuyen_mai);
            } else {
                card.title = 'Bạn đã sử dụng mã này rồi';
            }

            card.innerHTML = `
                <div style="display:flex; align-items:stretch;">
                    <!-- Phần bên trái giữ nguyên dạng text 2 dòng gọn gàng, không có icon phía trên -->
                    <div style="width:95px; background:${tagBg}; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:14px 8px; border-right:1px dashed ${bdColor}; flex-shrink:0;">
                        <div style="font-size:13px; font-weight:800; color:${tagColor}; text-align:center; line-height:1.2;">${km.gia_tri_hien_thi}</div>
                    </div>

                    <div style="flex:1; padding:12px 14px;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                            <span style="font-size:15px; font-weight:700; color:${disabled ? '#94a3b8' : '#0f172a'}; letter-spacing:0.5px; font-family:monospace;">${km.ma_khuyen_mai}</span>
                            ${topRightBadge}
                        </div>
                        <p style="margin:0 0 6px; font-size:12px; color:${disabled ? '#94a3b8' : '#374151'};">${km.ten_chuong_trinh}</p>
                        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                            ${minDon}
                            <span style="font-size:11px; color:#6b7280;">⏰ HSD: ${km.ngay_ket_thuc}</span>
                            <span style="font-size:11px; color:#6b7280;">Còn ${km.con_lai} lượt</span>
                        </div>
                    </div>
                    <!-- Nút chọn -->
                    <div style="display:flex; align-items:center; padding:0 14px; background:transparent;">
                        ${actionLabel}
                    </div>
                </div>
            `;
            list.appendChild(card);
        });
    } catch(e) {
        loading.innerHTML = '<p style="color:#dc2626; font-size:13px;">Có lỗi khi tải danh sách. Vui lòng thử lại.</p>';
        loading.style.display = 'block';
    }
}

function dongPopupVoucher() {
    const overlay = document.getElementById('popupVoucherOverlay');
    const popup = document.getElementById('popupVoucher');
    if (overlay) overlay.style.display = 'none';
    if (popup) popup.style.display = 'none';
}

function chonVoucher(ma) {
    const input = document.getElementById('ma_voucher_input');
    if (input) {
        input.disabled = false;
        input.value = ma;
    }
    
    dongPopupVoucher();
    
    apDungVoucher();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') dongPopupVoucher();
});

// --- 5. SỰ KIỆN KHỞI CHẠY TRANG ---
document.getElementById('address')?.addEventListener('change', async function() {
    const val = this.value.trim();
    if (val.length >= 3) {
        let foundCoord = false;
        try {
            const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(val)}&limit=1`);
            if (res.ok) {
                const data = await res.json();
                if (data && data.features && data.features.length > 0) {
                    const feat = data.features[0];
                    const lat = feat.geometry.coordinates[1];
                    const lng = feat.geometry.coordinates[0];
                    if (document.getElementById('latitude')) document.getElementById('latitude').value = lat.toFixed(8);
                    if (document.getElementById('longitude')) document.getElementById('longitude').value = lng.toFixed(8);
                    capNhatPhiShipTuToaDo(lat, lng);
                    foundCoord = true;
                }
            }
        } catch (e) {}

        if (!foundCoord) {
            capNhatPhiShipTuToaDo(null, null);
        }
    } else {
        capNhatPhiShipTuToaDo(null, null);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const addressEl = document.getElementById('address');
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');

    const latVal = latEl && latEl.value ? parseFloat(latEl.value) : null;
    const lngVal = lngEl && lngEl.value ? parseFloat(lngEl.value) : null;

    if (latVal && lngVal) {
        capNhatPhiShipTuToaDo(latVal, lngVal);
    } else {
        capNhatPhiShipTuToaDo(null, null);
    }
});

document.addEventListener('click', function(e) {
    const box = document.getElementById('addressSuggestions');
    if (box && !e.target.closest('#address') && !e.target.closest('#addressSuggestions')) {
        box.style.display = 'none';
    }
});

const termsCheck = document.getElementById('terms');
const termsErr = document.getElementById('termsError');

termsCheck?.addEventListener('change', function() {
    if (this.checked && termsErr) {
        termsErr.style.display = "none";
    }
});

document.getElementById('formXacNhan')?.addEventListener('submit', function(e) {
    if (termsCheck && !termsCheck.checked) {
        if (termsErr) {
            termsErr.style.display = "block";
        }
        e.preventDefault();
    } else {
        if (termsErr) {
            termsErr.style.display = "none";
        }
    }
});