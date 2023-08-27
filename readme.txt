=== Gaali ===
Contributors: anjanesh
Donate link: https://www.paypal.com/paypalme/anjanesh
Tags: toxicity, moderation
Requires at least: 6.2.2
Tested up to: 6.3
Stable tag: 1.0
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gaali removes harmful and unwanted comments before they can cause any damage. Its checks for toxicity based on TensorflowJS.

== Description ==

Gaali in Hindi means Slang which is Tocicity in today's jargon

Stop toxicity and form slang - Gaali effectively eliminates harmful and unwanted comments, preemptively preventing any potential damage.

== Frequently Asked Questions ==

= After posting a comment, why does it still say it's in moderation ? Isn't this supposed to be automated ? =

Yes, it's automated and there is no need for the wordpress admin to approve a good comment.
But to validate a comment for foul language and slangs, which is called a toxicity check, it takes time, like many many seconds, to detect it.

= Why does it take so much time for a toxicity check ? =

The toxicity check connects to TensorFlow, here using [TensorFlowJS](https://www.tensorflow.org/js "TensorFlow using JavaScript"), which is a Google's product and the data sets used are on Google's servers.

= So this connects to some 3rd party backend-service ? =

Yes, it connects to an API at toxicity.co.in which is again, my (free) service which runs on Deno at Deno Deploy - deno.com which in turn goes to Google for returning the results of toxicity. Sometimes it takes a minute or more to return the result and at times, it even times out. If it times out the admin has to manually validate the comment in wp-admin.

= Will this plugin remain free forever ? =

I want it to remain free forever and open-sourced.
There is so much of free stuff on the internet and even open-sourced.
I want to be part of the community and don't intend on commercializing it.
But if toxicity service at my deno.com account take a hit on many requests, I will then have to shell out minimum $10/mo ($2 per million requests per month, $0.30/GiB outbound data transfer)
Even then that's okay and I can fund that minimum amount, but if it goes beyong $10 a month, then I will seek funding of some sort.

== Screenshots ==

1. This is how the comment list in the backend looks with details of the toxicity parameters

== Changelog ==

= 0.5 =
* Just started, will update when I come across issues.

1. Comment check runs in the background
1. Neat table in comment's admin showcasing 6 toxic parameters and a master parameter Toxicity that will decide if the comment should be auto-approved or not
1. API Key not required for now, but will incorporate this if there are many users of this plugin and the usage is high

== Upgrade Notice ==

= 1.0 =
This is the first version.