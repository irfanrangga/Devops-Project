document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("categorySelect");
    const materialGroups = document.querySelectorAll(".material-group");
    const noCategoryMsg = document.getElementById("noCategoryMsg");
    const designRadios = document.querySelectorAll(".design-radio");
    const customDesignDiv = document.getElementById("customDesignDiv");

    // Subtotal dan biaya tambahan desain custom
    const subtotalText = document.getElementById("subtotalText");
    const designTypeRadios = document.querySelectorAll(
        'input[name="design_type"]'
    );
    let basePrice = 0;
    if (subtotalText) {
        basePrice =
            parseInt(subtotalText.textContent.replace(/[^\d]/g, "")) || 0;
    }
    const customFee = 5000;

    const designPriceEl = document.getElementById("designPrice");
    function updateDesignPrice() {
        const selected = document.querySelector(
            'input[name="design_type"]:checked'
        );
        if (!designPriceEl) return;
        if (selected && selected.value === "custom") {
            designPriceEl.textContent = "Rp" + (5000).toLocaleString("id-ID");
        } else {
            designPriceEl.textContent = "Rp" + (0).toLocaleString("id-ID");
        }
    }
    if (designTypeRadios && designTypeRadios.length > 0) {
        designTypeRadios.forEach((radio) => {
            radio.addEventListener("change", updateDesignPrice);
        });
        updateDesignPrice();
    }

    // Fungsi untuk reset semua radio button (agar tidak terkirim data salah)
    function uncheckAll() {
        document
            .querySelectorAll('input[name="material"]')
            .forEach((el) => (el.checked = false));
    }

    function showGroupFor(category) {
        materialGroups.forEach((group) => group.classList.add("hidden"));
        uncheckAll();

        if (!category) {
            if (noCategoryMsg) noCategoryMsg.classList.remove("hidden");
            return;
        }

        const targetGroup = document.querySelector(
            `.material-group[data-category="${category}"]`
        );
        if (targetGroup) {
            targetGroup.classList.remove("hidden");
            if (noCategoryMsg) noCategoryMsg.classList.add("hidden");
            // Check first material option by default
            const firstRadio = targetGroup.querySelector(
                'input[name="material"]'
            );
            if (firstRadio) firstRadio.checked = true;
        }
    }

    // Event Listener saat Kategori berubah
    if (categorySelect) {
        categorySelect.addEventListener("change", function () {
            showGroupFor(this.value);
        });

        // Trigger manual saat halaman dimuat (untuk handle old input saat validasi error)
        if (categorySelect.value !== "") {
            showGroupFor(categorySelect.value);
        }
    }

    // Handle desain standard/custom
    if (designRadios.length > 0 && customDesignDiv) {
        function updateDesignDiv() {
            const selected = document.querySelector(
                'input[name="design_type"]:checked'
            );
            if (selected && selected.value === "custom") {
                customDesignDiv.classList.remove("hidden");
            } else {
                customDesignDiv.classList.add("hidden");
                // Clear file input when switching back to standard
                const file = customDesignDiv.querySelector('input[type="file"]');
                if (file) file.value = null;
            }
        }

        designRadios.forEach((r) =>
            r.addEventListener("change", updateDesignDiv)
        );
        updateDesignDiv();
    }

    // Highlight selected option labels for material and warna
    function updateSelectedLabels(radioName) {
        const radios = document.querySelectorAll(`input[name="${radioName}"]`);
        radios.forEach((r) => {
            const lbl = document.querySelector(`label[for="${r.id}"]`);
            if (!lbl) return;
            if (r.checked) {
                lbl.classList.add(
                    "bg-blue-500",
                    "text-white",
                    "border-transparent",
                    "selected"
                );
                lbl.classList.remove("bg-white", "text-gray-700");
            } else {
                lbl.classList.remove(
                    "bg-blue-500",
                    "text-white",
                    "border-transparent",
                    "selected"
                );
                lbl.classList.add("bg-white", "text-gray-700");
            }
        });
    }

    // Attach change listeners and initialize state
    ["material", "warna"].forEach((name) => {
        document.querySelectorAll(`input[name="${name}"]`).forEach((r) => {
            r.addEventListener("change", () => updateSelectedLabels(name));
        });
        // init
        updateSelectedLabels(name);
    });

    // Quantity controls and total price calculation
    const unitPriceEl = document.getElementById("unitPrice");
    const totalPriceEl = document.getElementById("totalPrice");
    const qtyInput = document.getElementById("quantityInput");
    const plusBtn = document.getElementById("qtyPlus");
    const minusBtn = document.getElementById("qtyMinus");

    function formatRupiah(n) {
        return "Rp" + new Intl.NumberFormat("id-ID").format(n);
    }

    function updateTotal() {
        if (!unitPriceEl || !totalPriceEl || !qtyInput) return;
        const unit = parseInt(unitPriceEl.dataset.price) || 0;
        let qty = parseInt(qtyInput.value) || 1;
        const max = parseInt(qtyInput.dataset.max) || 9999;
        if (qty < 1) qty = 1;
        if (qty > max) qty = max;
        qtyInput.value = qty;
        // Hitung biaya desain custom
        let designCost = 0;
        const selectedDesign = document.querySelector('input[name="design_type"]:checked');
        if (selectedDesign && selectedDesign.value === "custom") {
            designCost = customFee;
        }
        // Update subtotalText dan totalPrice
        if (subtotalText) {
            subtotalText.textContent = formatRupiah((unit + designCost));
        }
        totalPriceEl.innerText = formatRupiah((unit + designCost) * qty);
    }

    if (plusBtn && minusBtn && qtyInput) {
        plusBtn.addEventListener("click", () => {
            const max = parseInt(qtyInput.dataset.max) || 9999;
            let v = parseInt(qtyInput.value) || 1;
            if (v < max) v++;
            qtyInput.value = v;
            updateTotal();
        });

        minusBtn.addEventListener("click", () => {
            let v = parseInt(qtyInput.value) || 1;
            if (v > 1) v--;
            qtyInput.value = v;
            updateTotal();
        });

        qtyInput.addEventListener("change", () => updateTotal());
    }

    // Update subtotal dan total saat desain berubah
    if (designTypeRadios && designTypeRadios.length > 0) {
        designTypeRadios.forEach((radio) => {
            radio.addEventListener("change", updateTotal);
        });
    }

    // initialize total
    updateTotal();

    // Handle file upload preview and drag-drop
    const fileUploadInput = document.getElementById("file-upload");
    const customDesignContainer = document.getElementById("customDesignDiv");

    if (fileUploadInput && customDesignContainer) {
        // Create preview container
        const previewContainer = document.createElement("div");
        previewContainer.id = "filePreviewContainer";
        previewContainer.className = "hidden mt-4";
        previewContainer.innerHTML = `
            <div class="flex items-center justify-between bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p id="fileName" class="font-semibold text-gray-800"></p>
                        <p id="fileSize" class="text-sm text-gray-500"></p>
                    </div>
                </div>
                <button type="button" id="removeFileBtn" class="text-red-500 hover:text-red-700">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        customDesignContainer.querySelector(".text-center").parentElement.insertAdjacentElement("afterend", previewContainer);

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
        }

        // Handle file selection
        function handleFileSelect(file) {
            if (!file) return;

            // Validate file type
            const allowedTypes = ["image/png", "image/jpeg", "image/jpg", "application/pdf"];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                alert("File type tidak didukung. Gunakan PNG, JPG, JPEG, atau PDF.");
                fileUploadInput.value = "";
                return;
            }

            if (file.size > maxSize) {
                alert("File terlalu besar. Maksimal 5MB.");
                fileUploadInput.value = "";
                return;
            }

            // Show preview
            document.getElementById("fileName").textContent = file.name;
            document.getElementById("fileSize").textContent = formatFileSize(file.size);
            previewContainer.classList.remove("hidden");
        }

        // File input change event
        fileUploadInput.addEventListener("change", (e) => {
            const file = e.target.files[0];
            handleFileSelect(file);
        });

        // Remove file button
        document.getElementById("removeFileBtn").addEventListener("click", () => {
            fileUploadInput.value = "";
            previewContainer.classList.add("hidden");
        });

        // Drag and drop events
        const uploadArea = customDesignContainer.querySelector(".text-center").parentElement;

        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ["dragenter", "dragover"].forEach((eventName) => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add("border-blue-500", "bg-blue-50");
            });
        });

        ["dragleave", "drop"].forEach((eventName) => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove("border-blue-500", "bg-blue-50");
            });
        });

        uploadArea.addEventListener("drop", (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                fileUploadInput.files = files;
                handleFileSelect(files[0]);
            }
        });
    }

    const button = document.getElementById('rating-button');
    const menu = document.getElementById('rating-menu');
    const text = document.getElementById('rating-text');
    const input = document.getElementById('rating-input');
    const arrow = document.getElementById('rating-arrow');
    const options = document.querySelectorAll('.rating-option');
    
    // Fungsi untuk membuka/menutup dropdown
    function toggleMenu() {
        const isClosed = menu.classList.contains('opacity-0');
        
        if (isClosed) {
            // Animasi Buka
            menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.add('opacity-100', 'scale-100');
            arrow.classList.add('rotate-180');
        } else {
            // Animasi Tutup
            closeMenu();
        }
    }
    function closeMenu() {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        arrow.classList.remove('rotate-180');
    }
    // Event listener saat tombol utama ditekan
    button.addEventListener('click', toggleMenu);
    // Event listener saat salah satu opsi bintang dipilih
    options.forEach(option => {
        option.addEventListener('click', function() {
            // Masukkan nilai angka ke input hidden
            input.value = this.getAttribute('data-value');
            // Ubah teks tombol menjadi opsi yang dipilih
            text.innerHTML = this.innerHTML;
            text.classList.remove('text-gray-500');
            text.classList.add('text-gray-900');
            
            closeMenu();
        });
    });
    // Menutup dropdown secara otomatis jika user mengklik area luar
    document.addEventListener('click', function(event) {
        const container = document.getElementById('rating-dropdown-container');
        if (!container.contains(event.target)) {
            closeMenu();
        }
    });

    // Tab functionality
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    function activateTab(tabName) {
        // Hide all tab contents
        tabContents.forEach((content) => {
            content.classList.add("hidden");
            content.classList.remove("block");
        });

        // Remove active class from all buttons
        tabButtons.forEach((btn) => {
            btn.classList.remove("active");
            btn.classList.remove("border-blue-500");
            btn.classList.add("border-transparent");
            btn.classList.remove("text-blue-500");
        });

        // Show active tab content
        const activeContent = document.getElementById(tabName);
        if (activeContent) {
            activeContent.classList.remove("hidden");
            activeContent.classList.add("block");
        }

        // Add active class to clicked button
        const activeButton = document.querySelector(
            `.tab-button[data-tab="${tabName}"]`
        );
        if (activeButton) {
            activeButton.classList.add("active");
            activeButton.classList.remove("border-transparent");
            activeButton.classList.add("border-blue-500");
            activeButton.classList.add("text-blue-500");
        }
    }

    // Attach click handlers to all tab divs
    tabButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            const tabName = button.getAttribute("data-tab");
            if (tabName) {
                activateTab(tabName);
            }
            return false;
        });
    });
    
    // Activate "detail" tab by default
    activateTab("detail");
});


function changeMainImage(thumbnail) {
        const mainImage = document.getElementById("main-product-image");
        mainImage.src = thumbnail.src;

        const thumbnails = document.querySelectorAll(".img-thumbnail");
        thumbnails.forEach(thumb => {
            thumb.classList.remove("border-blue-500", "border-2");
            thumb.classList.add("opacity-75");
        });

        thumbnail.classList.remove("opacity-75");
        thumbnail.classList.add("border-blue-500", "border-2");
    }