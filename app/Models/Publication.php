<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property string $title
 * @property string $abstract
 * @property string $keywords
 * @property string|null $journal_name
 * @property string|null $journal_url
 * @property string|null $indexing
 * @property string|null $doi
 * @property string|null $issn
 * @property string|null $publisher
 * @property \Illuminate\Support\Carbon|null $publication_date
 * @property string|null $volume
 * @property string|null $issue
 * @property string|null $pages
 * @property string|null $sumber_artikel
 * @property array<array-key, mixed>|null $tipe_publikasi
 * @property int $publication_type_id
 * @property string $file_path
 * @property bool $is_published
 * @property string $publication_status
 * @property string|null $loa_file_path
 * @property \Illuminate\Support\Carbon|null $loa_date
 * @property string|null $loa_number
 * @property \Illuminate\Support\Carbon|null $submission_date_to_publisher
 * @property \Illuminate\Support\Carbon|null $expected_publication_date
 * @property string|null $publisher_name
 * @property string|null $journal_name_expected
 * @property string|null $publication_agreement_notes
 * @property string|null $publication_notes
 * @property string $admin_status
 * @property string $dosen_status
 * @property string|null $admin_feedback
 * @property \Illuminate\Support\Carbon|null $admin_reviewed_at
 * @property string|null $dosen_feedback
 * @property \Illuminate\Support\Carbon $submission_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $hki_publication_date
 * @property string|null $hki_creator
 * @property string|null $hki_certificate
 * @property string|null $book_title
 * @property string|null $book_publisher
 * @property int|null $book_year
 * @property string|null $book_edition
 * @property string|null $book_editor
 * @property string|null $book_isbn
 * @property string|null $book_pdf
 * @property-read \App\Models\PublicationType $publicationType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAbstract($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAdminFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAdminReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAdminStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookEdition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookPdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookPublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereBookYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDosenFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDosenStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereExpectedPublicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereHkiCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereHkiCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereHkiPublicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIndexing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIssn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalNameExpected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereLoaDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereLoaFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereLoaNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublicationAgreementNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublicationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublicationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublicationTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublisherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereSubmissionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereSubmissionDateToPublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereSumberArtikel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTipePublikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereVolume($value)
 * @mixin \Eloquent
 */
class Publication extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'abstract',
        'keywords',
        'journal_name',
        'journal_url',
        'indexing',
        'doi',
        'issn',
        'publisher',
        'publication_date',
        'volume',
        'issue',
        'pages',
        'publication_type_id',
        'file_path',
        'is_published',
        'publication_status',
        'loa_file_path',
        'loa_date',
        'loa_number',
        'submission_date_to_publisher',
        'expected_publication_date',
        'publication_notes',
                    'publisher_name',
            'journal_name_expected',
            'publication_agreement_notes',
        'admin_status',
        'dosen_status',
        'admin_feedback',
        'dosen_feedback',
        'submission_date',
        'sumber_artikel',
        'tipe_publikasi',
        // HKI fields
        'hki_publication_date',
        'hki_creator',
        'hki_certificate',
        // Book fields
        'book_title',
        'book_publisher',
        'book_year',
        'book_edition',
        'book_editor',
        'book_isbn',
        'book_pdf',
        'admin_reviewed_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'submission_date' => 'datetime',
        'publication_date' => 'date',
        'tipe_publikasi' => 'array',
        'hki_publication_date' => 'date',
        'loa_date' => 'date',
        'submission_date_to_publisher' => 'date',
        'expected_publication_date' => 'date',
        'book_year' => 'integer',
        'admin_reviewed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function publicationType()
    {
        return $this->belongsTo(PublicationType::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper methods untuk status publikasi
    public function isDraft()
    {
        return $this->publication_status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->publication_status === 'submitted';
    }

    public function isAccepted()
    {
        return $this->publication_status === 'accepted';
    }

    public function isPublished()
    {
        return $this->publication_status === 'published';
    }

    public function hasLoA()
    {
        return !empty($this->loa_file_path);
    }

    public function getStatusBadgeClass()
    {
        switch ($this->publication_status) {
            case 'draft':
                return 'bg-gray-100 text-gray-800';
            case 'submitted':
                return 'bg-blue-100 text-blue-800';
            case 'accepted':
                return 'bg-green-100 text-green-800';
            case 'published':
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getStatusLabel()
    {
        switch ($this->publication_status) {
            case 'draft':
                return 'Draft';
            case 'submitted':
                return 'Submitted';
            case 'accepted':
                return 'Accepted (LoA)';
            case 'published':
                return 'Published';
            default:
                return 'Unknown';
        }
    }
}
