<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactService
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private PortfolioService $portfolioService;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        PortfolioService $portfolioService
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->portfolioService = $portfolioService;
    }

    /**
     * Send contact form emails in the user's language
     * 
     * @param Contact $contactFormData The contact form data
     * @param string $locale The user's locale (e.g., 'en', 'fr')
     * @return bool True if emails were sent successfully, false otherwise
     */
    public function sendContactEmails(Contact $contactFormData, string $locale): bool
    {
        try {
            $personalInfo = $this->portfolioService->getPersonalInfo();
            $adminEmail = $personalInfo['email'];
            $senderName = $personalInfo['name'];

            // Send email to admin
            $this->sendAdminEmail($contactFormData, $adminEmail, $locale);
            
            // Send confirmation email to user
            $this->sendUserConfirmationEmail($contactFormData, $adminEmail, $senderName, $locale);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Send notification email to the admin
     */
    private function sendAdminEmail(Contact $contactFormData, string $adminEmail, string $locale): void
    {
        $subject = $this->translator->trans('contact_email.admin_subject', [
            '%email%' => $contactFormData->getEmail()
        ], null, $locale);

        $nameLabel = $this->translator->trans('contact_email.name_label', [], null, $locale);
        $emailLabel = $this->translator->trans('contact_email.email_label', [], null, $locale);
        $messageLabel = $this->translator->trans('contact_email.message_label', [], null, $locale);

        $email = (new Email())
            ->from(new Address($contactFormData->getEmail()))
            ->to(new Address($adminEmail))
            ->subject($subject)
            ->html(
                '<p><strong>' . $nameLabel . ':</strong> ' . htmlspecialchars($contactFormData->getName()) . '</p>' .
                '<p><strong>' . $emailLabel . ':</strong> ' . htmlspecialchars($contactFormData->getEmail()) . '</p>' .
                '<p><strong>' . $messageLabel . ':</strong><br>' . nl2br(htmlspecialchars($contactFormData->getMessage())) . '</p>'
            );
        
        $this->mailer->send($email);
    }

    /**
     * Send confirmation email to the user
     */
    private function sendUserConfirmationEmail(Contact $contactFormData, string $adminEmail, string $senderName, string $locale): void
    {
        $subject = $this->translator->trans('contact_email.user_subject', [], null, $locale);
        
        $nameLabel = $this->translator->trans('contact_email.name_label', [], null, $locale);
        $emailLabel = $this->translator->trans('contact_email.email_label', [], null, $locale);
        $messageLabel = $this->translator->trans('contact_email.message_label', [], null, $locale);
        $thankYouMessage = $this->translator->trans('contact_email.thank_you_message', [], null, $locale);
        $regards = $this->translator->trans('contact_email.regards', [], null, $locale);

        $email = (new Email())
            ->from(new Address($adminEmail, $senderName))
            ->to(new Address($contactFormData->getEmail()))
            ->subject($subject)
            ->html(
                '<p><strong>' . $nameLabel . ':</strong> ' . htmlspecialchars($contactFormData->getName()) . '</p>' .
                '<p><strong>' . $emailLabel . ':</strong> ' . htmlspecialchars($contactFormData->getEmail()) . '</p>' .
                '<p><strong>' . $messageLabel . ':</strong><br>' . nl2br(htmlspecialchars($contactFormData->getMessage())) . '</p>' .
                '<br>' .
                '<p>' . $thankYouMessage . '</p>' .
                '<p>' . $regards . ',</p>' .
                '<p>' . $senderName . '</p>'
            );
        
        $this->mailer->send($email);
    }
}
