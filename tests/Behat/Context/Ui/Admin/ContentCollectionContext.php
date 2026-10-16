<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Behat\Step\Then;
use Behat\Step\When;
use Sylius\CmsPlugin\Form\Type\ContentElements\HeadingContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\SingleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TaxonsListContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\TextareaContentElementType;
use Tests\Sylius\CmsPlugin\Behat\Element\Admin\ContentElementsCollectionElementInterface;
use Webmozart\Assert\Assert;

class ContentCollectionContext implements Context
{
    public function __construct(
        private ContentElementsCollectionElementInterface $contentElementsCollectionElement,
        private ?Session $probeSession = null,
    ) {
    }

    #[When('I add a textarea content element with :content content')]
    public function iAddATextareaContentElementWithContent(string $content): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            TextareaContentElementType::TYPE,
            $content,
        );
    }

    #[When('I add a single media content element with name :name')]
    public function iAddASingleMediaContentElementWithName(string $name): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            SingleMediaContentElementType::TYPE,
            $name,
        );
    }

    #[When('I add a multiple media content element with names :firstMediaName and :secondMediaName')]
    public function iAddAMultipleMediaContentElementWithNames(string ...$mediaNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            MultipleMediaContentElementType::TYPE,
            $mediaNames,
        );
    }

    #[When('I add a heading content element with type :type and :content content')]
    public function iAddAHeadingContentElementWithTypeAndContent(string $type, string $content): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            HeadingContentElementType::TYPE,
            [
                'heading_type' => $type,
                'heading' => $content,
            ],
        );
    }

    #[When('I add a products carousel content element with :firstProductName and :secondProductName products')]
    public function iAddAProductsCarouselContentElementWithProducts(string ...$productsNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsCarouselContentElementType::TYPE,
            $productsNames,
        );
    }

    #[When('I add a products carousel by taxon content element with :taxon taxonomy')]
    public function iAddAProductsCarouselByTaxonContentElementWithTaxon(string $taxon): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsCarouselByTaxonContentElementType::TYPE,
            $taxon,
        );
    }

    #[When('I add a products grid content element with :firstProductName and :secondProductName products')]
    public function iAddAProductsGridContentElementWithProducts(string ...$productsNames): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsGridContentElementType::TYPE,
            $productsNames,
        );
    }

    #[When('I add a products grid by taxon content element with :taxon taxonomy')]
    public function iAddAProductsGridByTaxonContentElementWithTaxon(string $taxon): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            ProductsGridByTaxonContentElementType::TYPE,
            $taxon,
        );
    }

    #[When('I add a taxons list content element with :firstTaxon and :secondTaxon taxonomy')]
    public function iAddATaxonsListContentElementWithTaxons(string ...$taxons): void
    {
        $this->contentElementsCollectionElement->addContentElementOfTypeWithContent(
            TaxonsListContentElementType::TYPE,
            $taxons,
        );
    }

    #[When('I change textarea content element value to :value')]
    public function iChangeTextareaContentElementValueTo(string $value): void
    {
        $this->contentElementsCollectionElement->updateContentElementOfType(
            TextareaContentElementType::TYPE,
            $value,
        );
    }

    #[When('I select :templateName content template')]
    public function iSelectContentTemplate(string $templateName): void
    {
        $this->contentElementsCollectionElement->selectTemplate($templateName);
    }

    #[When('I confirm that I want to use this template')]
    public function iConfirmThatIWantToUseThisTemplate(): void
    {
        $this->contentElementsCollectionElement->applyTemplate();
    }

    #[Then('I should see :content in the textarea content element')]
    public function iShouldSeeNewContentInTheTextareaContentElement(string $content): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElementWithContent(
            TextareaContentElementType::TYPE,
            $content,
        ));
    }

    #[Then('I should see newly created :contentElement content element in Content elements section')]
    public function iShouldSeeNewlyCreatedContentElementInContentElementsSection(string $contentElement): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElement($contentElement));
    }

    #[Then('I should see a :type element with :content content')]
    #[Then('I should see a :type element with :firstContent and :secondContent content')]
    public function iShouldSeeATypeElementWithContent(string $type, string ...$content): void
    {
        Assert::true($this->contentElementsCollectionElement->hasContentElementWithContent($type, $content));
    }

    #[When('I delete the :contentElement content element')]
    public function iDeleteTheContentElement(string $contentElement): void
    {
        $this->contentElementsCollectionElement->removeContentElement($contentElement);
    }

    #[Then('I should not see :contentElement content element in the Content elements section')]
    public function iShouldNotSeeContentElementInTheContentElementsSection(string $contentElement): void
    {
        Assert::false($this->contentElementsCollectionElement->hasContentElement($contentElement));
    }

    #[Then('the :ordinal content element should be a :type element')]
    public function theContentElementAtPositionShouldBeOfType(string $ordinal, string $type): void
    {
        Assert::same(
            $this->contentElementsCollectionElement->getContentElementTypeAtPosition($this->parseOrdinal($ordinal)),
            $type,
        );
    }

    #[Then('the :ordinal content element should contain :content')]
    public function theContentElementAtPositionShouldContain(string $ordinal, string $content): void
    {
        Assert::contains(
            $this->contentElementsCollectionElement->getContentElementContentAtPosition($this->parseOrdinal($ordinal)),
            $content,
            sprintf('Expected the %s content element to contain "%s", but it did not.', $ordinal, $content),
        );
    }

    #[Then('the move up button of the :ordinal content element should be disabled')]
    public function theMoveUpButtonOfTheContentElementShouldBeDisabled(string $ordinal): void
    {
        Assert::true(
            $this->contentElementsCollectionElement->isContentElementMoveUpButtonDisabled($this->parseOrdinal($ordinal)),
        );
    }

    #[Then('the move down button of the :ordinal content element should be disabled')]
    public function theMoveDownButtonOfTheContentElementShouldBeDisabled(string $ordinal): void
    {
        Assert::true(
            $this->contentElementsCollectionElement->isContentElementMoveDownButtonDisabled($this->parseOrdinal($ordinal)),
        );
    }

    #[When('I insert a textarea content element after the :ordinal content element')]
    public function iInsertATextareaContentElementAfterTheContentElement(string $ordinal): void
    {
        $this->contentElementsCollectionElement->insertContentElementAfterPosition(
            TextareaContentElementType::TYPE,
            $this->parseOrdinal($ordinal),
        );
    }

    #[When('I insert a textarea content element before the :ordinal content element')]
    public function iInsertATextareaContentElementBeforeTheContentElement(string $ordinal): void
    {
        $this->contentElementsCollectionElement->insertContentElementBeforePosition(
            TextareaContentElementType::TYPE,
            $this->parseOrdinal($ordinal),
        );
    }

    #[When('I move the :ordinal content element up')]
    public function iMoveTheContentElementUp(string $ordinal): void
    {
        $this->contentElementsCollectionElement->moveContentElementUp($this->parseOrdinal($ordinal));
    }

    #[When('I move the :ordinal content element down')]
    public function iMoveTheContentElementDown(string $ordinal): void
    {
        $this->contentElementsCollectionElement->moveContentElementDown($this->parseOrdinal($ordinal));
    }

    private function parseOrdinal(string $ordinal): int
    {
        return (int) preg_replace('/\D/', '', $ordinal);
    }

    #[When('I start recording Live Component requests')]
    public function iStartRecordingLiveComponentRequests(): void
    {
        $this->probeSession?->executeScript(<<<'JS'
            window.__probeRequests = [];
            if (!window.__probeInstalled) {
                window.__probeInstalled = true;

                const serialiseBody = (body) => {
                    if (!body) {
                        return '';
                    }
                    if (body instanceof FormData) {
                        return [...body.entries()].map(([k, v]) => k + '=' + v).join('&');
                    }

                    return typeof body === 'string' ? body : String(body);
                };

                const originalFetch = window.fetch;
                window.fetch = function (input, init) {
                    try {
                        window.__probeRequests.push({
                            via: 'fetch',
                            url: String(typeof input === 'string' ? input : (input && input.url) || ''),
                            method: (init && init.method) || 'GET',
                            body: serialiseBody(init && init.body),
                        });
                    } catch (e) { /* a probe must never break the page */ }

                    return originalFetch.apply(this, arguments);
                };

                const originalOpen = XMLHttpRequest.prototype.open;
                const originalSend = XMLHttpRequest.prototype.send;
                XMLHttpRequest.prototype.open = function (method, url) {
                    this.__probeMethod = method;
                    this.__probeUrl = url;

                    return originalOpen.apply(this, arguments);
                };
                XMLHttpRequest.prototype.send = function (body) {
                    try {
                        window.__probeRequests.push({
                            via: 'xhr',
                            url: String(this.__probeUrl || ''),
                            method: String(this.__probeMethod || ''),
                            body: serialiseBody(body),
                        });
                    } catch (e) { /* as above */ }

                    return originalSend.apply(this, arguments);
                };
            }
            JS);
    }

    #[Then('I dump the recorded Live Component requests')]
    public function iDumpTheRecordedLiveComponentRequests(): void
    {
        $captured = $this->probeSession?->evaluateScript('JSON.stringify(window.__probeRequests || [])');

        fwrite(\STDERR, "\n===== LIVE COMPONENT PAYLOADS =====\n");
        fwrite(\STDERR, is_string($captured) ? $captured : '(nothing captured)');
        fwrite(\STDERR, "\n===== END PAYLOADS =====\n");

        $selects = $this->probeSession?->evaluateScript(<<<'JS'
            JSON.stringify([...document.querySelectorAll('select[data-controller*="autocomplete"]')].map((s) => ({
                name: s.name,
                options: [...s.options].map((o) => ({ value: o.value, text: o.textContent.trim(), selectedProp: o.selected, selectedAttr: o.hasAttribute('selected') })),
                tomSelectValue: s.tomselect ? s.tomselect.getValue() : '(no tomselect)',
            })));
            JS);

        fwrite(\STDERR, "\n===== RENDERED AUTOCOMPLETE SELECTS =====\n");
        fwrite(\STDERR, is_string($selects) ? $selects : '(nothing captured)');
        fwrite(\STDERR, "\n===== END SELECTS =====\n");
    }
}
