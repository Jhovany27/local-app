<div
    wire:ignore
    x-data="mapComponent()"
    x-init="initMap()"
    x-on:set-coordinates.window="
        $wire.set('data.drc_latitud', $event.detail.lat);
        $wire.set('data.drc_longitud', $event.detail.lng);
    ">
    <div id="map" style="height: 400px;"></div>
</div>

<script>
    function mapComponent() {
        return {
            map: null,
            marker: null,

            initMap() {

                if (typeof L === 'undefined') {
                    setTimeout(() => this.initMap(), 100);
                    return;
                }

  
                if (this.map) return;

                this.map = L.map('map').setView([18.0, -92.9], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);

                this.map.on('click', (e) => {
                    let lat = e.latlng.lat;
                    let lng = e.latlng.lng;

                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    this.marker = L.marker([lat, lng]).addTo(this.map);

                
                    window.dispatchEvent(new CustomEvent('set-coordinates', {
                        detail: {
                            lat,
                            lng
                        }
                    }));
                });
            }
        }
    }
</script>