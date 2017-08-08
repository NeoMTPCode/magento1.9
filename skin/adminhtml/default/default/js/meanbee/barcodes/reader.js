'use strict';
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var searchBox = document.getElementById('global_search'),
            barcodeSearchButton = document.createElement('button'),
            barcodeImage = document.createElement('img');
        // location.protocol + '//' + location.host +
        barcodeImage.src = '/skin/adminhtml/default/default/images/meanbee/barcodes/barcode.png';
        barcodeSearchButton.appendChild(barcodeImage);
        barcodeSearchButton.id = 'barcode-search-button';
        searchBox.parentElement.insertBefore(barcodeSearchButton, searchBox);

        var modalElement = document.createElement('div');
        modalElement.className = 'modal';
        modalElement.innerHTML = '<div class="modal-inner"><a rel="modal:close">X</a><div class="modal-content"></div></div>';
        document.body.appendChild(modalElement);
        var modal = new VanillaModal({ onClose: Quagga.stop });

        var videoRegion = document.createElement('div');
        videoRegion.id = 'barcode-live';
        document.body.appendChild(videoRegion);

        barcodeSearchButton.addEventListener('click', function () {
            Quagga.init({
                inputStream : {
                    name : 'Live',
                    type : 'LiveStream',
                    target: document.querySelector('#barcode-live')
                },
                decoder : {
                    readers : Meanbee.Barcodes.config.readers
                },
                locator: {
                    patchSize: 'medium',
                    halfSample: true
                },
                debug: {
                    showCanvas: true
                },
                numOfWorkers: 4,
                locate: true
            }, function(err) {
                if (err) {
                    console.log(err);
                    return
                }
                console.log('Initialization finished. Ready to start');
                Quagga.start();
            });
            Quagga.onProcessed(function (result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                    drawingCanvas = Quagga.canvas.dom.overlay;

                if (result) {
                    if (result.boxes) {
                        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute('width')), parseInt(drawingCanvas.getAttribute('height')));
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: 'green', lineWidth: 2});
                        });
                    }

                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: '#00F', lineWidth: 2});
                    }

                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                    }
                }
            });
            Quagga.onDetected(function (result) {
                //Delete last digit in result code
                var tmp = result.codeResult.code;
                if(tmp.toString().length == 13){
                    tmp = result.codeResult.code.toString().slice(0,-1);
                }
                searchBox.value = tmp;

                Quagga.stop();
                modal.close();
                searchBox.dispatchEvent(new Event('keydown'));
            });
            modal.open('#barcode-live');
        });
    });
})();