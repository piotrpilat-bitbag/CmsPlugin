<p align="center">
    <a href="https://sylius.com" target="_blank">
        <picture>
          <source media="(prefers-color-scheme: dark)" srcset="https://media.sylius.com/sylius-logo-800-dark.png">
          <source media="(prefers-color-scheme: light)" srcset="https://media.sylius.com/sylius-logo-800.png">
          <img alt="Sylius Logo" src="https://media.sylius.com/sylius-logo-800.png">
        </picture>
    </a>
</p>

<h1 align="center">CMS Plugin</h1>

<p align="center">
    <a href="https://sylius.com/plugins/" target="_blank">
        <img src="https://sylius.com/assets/badge-official-sylius-plugin.png" width="200" alt="Official Sylius Plugin">
    </a>
</p>

<p align="center">This plugin provides seamless CMS capabilities for Sylius stores</p>

<p align="center">Extend your Sylius store with powerful content management features. Create, edit, and organize pages with ease – all fully integrated into the Sylius ecosystem.</p>

---

## Documentation

📖 Full documentation is available here:
👉 [CMS Plugin Documentation](https://docs.sylius.com/cms-plugin)

## Configuration

### Restricting uploaded media MIME types

By default, any file can be uploaded in the admin media section (beyond the `image/*` and `video/*`
sanity check performed for the image and video providers). You can narrow that down per media type:

```yaml
# config/packages/sylius_cms.yaml
sylius_cms:
    media:
        allowed_mime_types:
            image: ['image/jpeg', 'image/png', 'image/webp']
            video: ['video/mp4']
            file: ['application/pdf', 'text/*']
```

The whole section is optional - a media type that is not listed (or has an empty list) accepts any
file, which is the default behaviour. Wildcards such as `image/*` are supported, and the MIME type is
detected from the file contents, not from its extension. The configured types are also rendered as the
`accept` attribute of the upload field, so the browser file picker pre-filters them.

## Security issues

If you think that you have found a security issue, please do not use the issue tracker and do not post it publicly.
Instead, all security issues must be sent to `security@sylius.com`

## Community

For online communication, we invite you to chat with us and other users on [Sylius Slack](https://sylius.com/slack).

## License

This plugin is released under the [MIT License](LICENSE).

## Telemetry

This plugin enforces telemetry data collection when used with Sylius.
Details are described in [TELEMETRY_POLICY.md](./TELEMETRY_POLICY.md).
