@php
    $openpanelClientId = config('services.openpanel.client_id');
@endphp

@if (!empty($openpanelClientId))
    <script>
        window.op = window.op || function () {
            var q = [];
            var fn = function () {
                if (arguments.length) {
                    q.push([].slice.call(arguments));
                }
            };

            return new Proxy(fn, {
                get: function (_target, prop) {
                    if (prop === 'q') {
                        return q;
                    }

                    return function () {
                        q.push([prop].concat([].slice.call(arguments)));
                    };
                },
                has: function (_target, prop) {
                    return prop === 'q';
                },
            });
        }();

        window.op('init', {
            clientId: @json($openpanelClientId),
            trackScreenViews: true,
            trackOutgoingLinks: true,
            trackAttributes: true,
        });
    </script>
    <script src="https://openpanel.dev/op1.js" defer async></script>
@endif
