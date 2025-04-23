<script src="/public/js/jquery.min.js"></script>
<div class="mx-auto bg-white p-5 rounded-lg shadow-lg">
    <h4 class="text-lg font-semibold mb-4"><?php echo $title; ?></h4>
    <div class="flex justify-between items-center mb-4">
        <div class="text-gray-600">
            <label for="rowsPerPage" class="mr-1">Xem</label>
            <select id="rowsPerPage" class="border border-gray-300 rounded px-2 py-1">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
            </select>
            <label for="rowsPerPage" class="ml-1">mục</label>
        </div>
        <input type="text" id="searchInput" placeholder="Tìm kiếm..."
            class="border border-gray-300 rounded-lg px-4 py-1 text-sm text-gray-600 w-40 lg:w-56">
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead id="tableHeader">
            </thead>
            <tbody id="tableData">
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-4 space-x-2">
        <button id="prevPage" class="px-2 py-1 rounded-lg text-gray-600">&lsaquo;</button>
        <div id="paginationButtons" class="flex space-x-2"></div>
        <button id="nextPage" class="px-2 py-1 rounded-lg text-gray-600">&rsaquo;</button>
    </div>
</div>

<script>
    let currentPage = 1;
    let rowsPerPage = parseInt($("#rowsPerPage").val(), 10);

    function renderTableHeader() {
        const tableHeader = $("#tableHeader");
        tableHeader.empty();

        const headerRow = Object.keys(columnTitles).map(key => `<th class="px-4 py-2 border font-normal whitespace-nowrap">${columnTitles[key]}</th>`).join('');
        tableHeader.append(`<tr class="text-gray-800">${headerRow}</tr>`);
    }

    function renderTable(data) {
        const tableData = $("#tableData");
        tableData.empty();

        if (data.length === 0) {
            tableData.append(`
                <tr>
                    <td colspan="${Object.keys(columnTitles).length}" class="text-center px-4 py-4 text-gray-500">Không tìm thấy dòng nào phù hợp</td>
                </tr>
            `);
        } else {
            data.forEach(item => {
                const row = Object.keys(columnTitles).map(key => {
                    if (key === 'action') {
                        const { action, ...itemWithoutAction } = item;
                        const itemString = encodeURIComponent(JSON.stringify(itemWithoutAction));
                        const buttons = item.action.map(button => `
                            <button class="px-3 py-1 rounded ${button.class}" onclick="${button.onclick}('${itemString}')">
                                ${button.label}
                            </button>
                        `).join(' ');
                        return `<td class="px-4 py-4 border whitespace-nowrap">${buttons}</td>`;
                    }
                    return `<td class="px-4 py-4 border whitespace-nowrap">${item[key]}</td>`;
                }).join('');
                tableData.append(`<tr class="text-gray-500 overflow-x-auto">${row}</tr>`);
            });
        }
    }


    function updatePaginationButtons() {
        const totalPages = Math.ceil(data.length / rowsPerPage);
        const paginationButtons = $("#paginationButtons");
        paginationButtons.empty();

        let startPage = Math.max(currentPage - 1, 1);
        let endPage = Math.min(startPage + 2, totalPages);

        if (endPage - startPage < 2) {
            startPage = Math.max(endPage - 2, 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationButtons.append(`
                <button class="px-4 py-2 rounded-lg ${i === currentPage ? 'bg-custom-background text-white' : 'text-gray-600'}" data-page="${i}">${i}</button>
            `);
        }

        paginationButtons.find("button").click(function () {
            currentPage = parseInt($(this).attr("data-page"), 10);
            paginateData();
            updatePaginationButtons();
        });

        $("#prevPage").prop("disabled", currentPage === 1);
        $("#nextPage").prop("disabled", currentPage === totalPages);
    }

    function paginateData() {
        const start = (currentPage - 1) * rowsPerPage;
        const paginatedData = data.slice(start, start + rowsPerPage);
        renderTable(paginatedData);
        updatePaginationButtons();
    }

    function removeVietnameseAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d").replace(/Đ/g, "D");
    }

    $("#searchInput").on("input", function () {
        const searchTerm = removeVietnameseAccents($(this).val().toLowerCase());
        const filteredData = data.filter(item =>
            Object.values(item).some(value =>
                removeVietnameseAccents(value.toString().toLowerCase()).includes(searchTerm)
            )
        );
        renderTable(filteredData.slice(0, rowsPerPage));
        currentPage = 1;
        updatePaginationButtons();
    });

    $("#prevPage").click(function () {
        if (currentPage > 1) {
            currentPage--;
            paginateData();
        }
    });

    $("#nextPage").click(function () {
        if (currentPage < Math.ceil(data.length / rowsPerPage)) {
            currentPage++;
            paginateData();
        }
    });

    $("#rowsPerPage").change(function () {
        rowsPerPage = parseInt($(this).val(), 10);
        currentPage = 1;
        paginateData();
    });

    renderTableHeader();
    paginateData();

    window.addEventListener('dataReady', function(event) {
        renderTableHeader();
        paginateData();
    });
</script>