<x-mail::message>
# Website Renewal Notice

Hello {{ $client->first_name }},

Your website **{{ $client->website_name }}** ({{ $client->website_link }}) is set to renew on **{{ $client->renew_date->format('d M Y') }}**.

There are **{{ $daysRemaining }}** days remaining until the renewal date.

Please ensure you make the necessary arrangements for renewal to avoid any service interruption.
For any assistance, please contact us at [arewasmart001@gmail.com](mailto:arewasmart001@gmail.com) or call us at [08064333983](tel:08064333983).

<x-mail::button :url="$client->website_link ?? '#'">
Visit Website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
