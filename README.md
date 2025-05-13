# [![](https://bitbag.io/wp-content/uploads/2021/08/ACL.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl)

# BitBag SyliusACLPlugin

----

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us to work  together, feel free to reach out. You will find out more about our professional services, technologies, and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl).

Like what we do? Want to join us? Check out our job listings on our [career page](https://bitbag.io/career/?utm_source=github&utm_medium=referral&utm_campaign=career). Not familiar with Symfony & Sylius yet, but still want to start with us? Join our [academy](https://bitbag.io/pl/akademia?utm_source=github&utm_medium=url&utm_campaign=akademia)!


## Table of Content

***

* [Support](#we-are-here-to-help)
* [Installation](#installation)
* [Usage](#usage)
* [Customization](#customization)
* [About us](#about-us)
    * [Community](#community)
* [Demo](#demo-sylius-shop)
* [License](#license)
* [Contact](#contact)

## We are here to help
This **open-source plugin was developed to help the Sylius community**. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl)

## Installation

---
### Requirements

We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.0          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

----

### Full installation guide
- [See the full installation guide](doc/installation.md)

## Usage

### How to add a new permission?

Let’s assume that you would like to add a new permission to ACL. You will need to add these few lines to the `config.yml` :

```yaml
# config/packages/_sylius.yaml
...

bit_bag_sylius_acl:
    permissions:
        # Each permission must have a unique id, if you want the route to be protected, as id you need to enter the name route.
        app_admin_product_import:
            parent: data_transfer # Here, specify parent in the permission tree. 
            label: product_import # Here, specify the name that will be displayed in the admin panel.
            enabled: true # Here you specify whether the permission is to be active, this field is not required, by default is set to true.
```

You can also add permission while defining the route. However, this will not work when you have defined or imported permissions with the same id in `config.yml`:

```yaml
# config/packages/_sylius.yaml
...
         
app_admin_product_import:
    path: /admin/products/import
    methods: [GET]
    defaults:
        _bitbag_sylius_acl_plugin:
            parent: data_transfer
            label: product_import
            enabled: true
```

For this permission you will need to add translations:

- `bitbag_sylius_acl_plugin.parent.data_transfer`
- `bitbag_sylius_acl_plugin.action.product_import`

### How to modify a permission?

If you would like to modify an existing permission of for example the permission to Payment complete, If you would like to have another label or parent:

```yaml
# config/packages/_sylius.yaml
...

bit_bag_sylius_acl:
    permissions:
        sylius_admin_order_payment_complete:
            parent: orders_shop
            label: order_payment_complete
```

You can also modify the permission on the [route is overwritten](https://symfony.com/doc/3.4/bundles/override.html#routing), only this will not work when you have defined or imported permissions with the same id in `config.yml`:

```yaml
# config/packages/_sylius.yaml
...

sylius_admin_order_payment_complete:
    path: /admin/orders/{orderId}/payments/{id}/complete
    methods: [PUT]
    defaults:
        ...
        
        _bitbag_sylius_acl_plugin:
            parent: orders_shop
            label: order_payment_complete
```

You can find the default configuration of some permissions under the [src/Resources/config/permissions.yml](/src/Resources/config/permissions.yml) path

### How to delete the permission?

If you want to remove the permission, you have to overwrite the permission configuration and and set the enabled field to `false`:

```yaml
# config/packages/_sylius.yaml
...

bit_bag_sylius_acl:
    permissions:
        sylius_admin_order_payment_complete:
            enabled: false
```

or for overwriting the route, only this will not work when you have defined or imported permissions with the same id in `config.yml`:

```yaml
# config/packages/_sylius.yaml
...

sylius_admin_order_payment_complete:
    path: /admin/orders/{orderId}/payments/{id}/complete
    methods: [PUT]
    defaults:
        ...
        
        _bitbag_sylius_acl_plugin:
            enabled: false
```

### Customizing the user's view

When the user does not have permissions for the given route, the Twig `path(), url()` functions will return `ACCESS_DENIED`. You will be able to adjust the view using the css and javascript selectors. For example:

 ```css
a[href="ACCESS_DENIED"].button {
    display: none !important;
}
 ```

More examples can be found under the [src/Resources/public/*](/src/Resources/public/) path.

You can also use the twig function:

```twig
{% if bitbag_acl_has_permission("sylius_admin_order_payment_complete") %}
    ...
    
{% endif %}
```

## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

Run the below command to see what Symfony services are shared with this plugin:
 
```bash
$ bin/console debug:container bitbag_sylius_acl_plugin
```

# About us

---

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo, and Pimcore for PIM, eZ Platform for CMS, and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.</br>
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.</br>
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**. Based on a detailed analysis of the goals and needs of a given organization, we create dedicated systems and applications that let businesses grow.<br>
Our team is fluent in **Polish, English, German and, French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 50+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 120+ projects **delivered** on top of Sylius,
- 25+ **countries** of BitBag’s customers,
- 4+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl) or send us an e-mail at hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2021/08/sylius-badges-transparent-wide.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl)

## Community

---- 

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `bitbag: bitbag` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl)

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License

---

The license terms are described in the LICENSE file of this repository.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2021/08/badges-bitbag.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_acl)
